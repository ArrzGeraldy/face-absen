<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\OfficeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        $jabatanId  = $request->jabatan;
        $search     = $request->search;
        $startDate  = $request->start_date ?? $today;
        $status  = $request->status;

        $query = Karyawan::query()
            ->join('jabatans', 'jabatans.id', '=', 'karyawans.jabatan_id')
            ->leftJoin('absens', function ($join) use ($startDate) {
                $join->on('absens.karyawan_id', '=', 'karyawans.id')
                      ->whereDate('absens.date', $startDate);
            })->where('karyawans.is_active', true)
            ->select(
                'karyawans.nama',
                'karyawans.photo',
                'karyawans.jabatan_id',

                'jabatans.nama as jabatan_nama',


                'absens.id as absen_id',

                'absens.check_in_time',
                'absens.check_in_latitude',
                'absens.check_in_longitude',
                'absens.check_in_distance',
                'absens.check_in_face_similarity',
                
                'absens.check_out_time',
                'absens.check_out_face_similarity',
                'absens.check_out_distance',

                'absens.is_late',
                'absens.date',
                'absens.status as status_absen',
            );

        // Filter by jabatan_id
        if ($jabatanId) {
            $query->where('karyawans.jabatan_id', $jabatanId);
        }

        // Search nama karyawan
        if ($search) {
            $query->where('karyawans.nama', 'LIKE', "%$search%");
        }

        // filter by status
        if ($status) {

            if ($status === 'Terlambat') {
                // Khusus status terlambat
                $query->where('absens.is_late', true);
            } else {
                // Filter berdasarkan enum status absen
                $query->where('absens.status', $status);
            }
        }


        $data = $query->get();

        $jabatans = Jabatan::all();

        return view('admin.absen.index', compact('data','jabatans','today'));
    }

    // view untuk karyawan absen
    public function absenKaryawan(){
        $id = Auth::id();
        $karyawan = Karyawan::where('user_id', $id)->firstOrFail();
                $today = now()->toDateString();
                $absen = Absen::where('karyawan_id', $karyawan->id)
                    ->where('date', $today)
                    ->first();
        return view('absen', compact('karyawan','absen'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'face_distance' => 'required|numeric',
        ]);

        $userId = Auth::id();

        // Ambil data karyawan
        $karyawan = Karyawan::where('user_id', $userId)->firstOrFail();

        $today = now()->toDateString();

        // Ambil setting kantor
        $office = OfficeSetting::first();
        if (!$office) {
            return response()->json([
                "status" => "error",
                "message" => "Office setting belum diatur"
            ], 400);
        }

        // ============================
        //  1) Hitung Jarak FE ke Kantor
        // ============================
        $distance = $this->calculateDistance(
            $request->lat,
            $request->lon,
            $office->latitude,
            $office->longitude
        );

        if ($distance > $office->radius) {
            return response()->json([
                "status" => "error",
                "message" => "Anda berada di luar radius kantor ($distance m)"
            ], 400);
        }

        // ===========================================
        //  2) Face validation (distance < 0.45 allowed)
        // ===========================================
        if ($request->face_distance > 0.45) {
            return response()->json([
                "status" => "error",
                "message" => "Wajah tidak cocok (face distance terlalu jauh)"
            ], 403);
        }

        // =======================================
        //  3) Apakah sudah ada absen hari ini?
        // =======================================
        $absen = Absen::where('karyawan_id', $karyawan->id)
                    ->where('date', $today)
                    ->first();

        // ====================
        //  4) CHECK IN
        // ====================
        if (!$absen) {
            // Ambil objek Carbon saat ini
            $currentTime = now();

            // --- Logika Penentuan Status ---

            // Konversi jam_masuk ke Carbon hari ini (sudah benar, ini adalah objek Carbon)
            $jamMasuk = Carbon::createFromFormat('H:i:s', $office->jam_masuk);

            // Tambahkan toleransi (ini adalah objek Carbon)
            $jamMasukDenganToleransi = $jamMasuk->copy()->addMinutes($office->toleransi_terlambat);

            // Bandingkan waktu dari $currentTime (objek Carbon)
            // dengan waktu batas ($jamMasukDenganToleransi, objek Carbon)
            // Metode ->greaterThan() atau ->gt() berfungsi di sini.
            // Kita perlu memastikan perbandingan dilakukan dengan waktu yang benar (misalnya, jam saat ini vs jam masuk hari ini).
            // Cara paling mudah adalah membandingkan waktu:
            $isLate = $currentTime->greaterThan($jamMasukDenganToleransi);

            $absen = Absen::create([
                'karyawan_id' => $karyawan->id,
                'date'        => $today,
                'check_in_time' => now()->format('H:i:s'),
                'check_in_latitude' => $request->lat,
                'check_in_longitude' => $request->lon,
                'check_in_distance' => $distance,
                'check_in_face_similarity' => 1 - $request->face_distance, // 1.0 = mirip
                'status' => "Hadir",
                "is_late" => $isLate
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Check-in berhasil",
                "type" => "check-in",
                "absen" => $absen
            ]);
        }

        // ====================
        //  5) CHECK OUT
        // ====================
        if (!$absen->check_out_time) {

            // waktu sekarang
            $currentTime = now();

            // jam keluar kantor pada hari ini
            $jamKeluar = now()->setTimeFromTimeString($office->jam_pulang);

            // jika jam pulang melewati tengah malam (shift malam)
            // misalnya jam masuk 22:00, jam pulang 00:30
            if ($office->jam_pulang < $office->jam_masuk) {
                $jamKeluar->addDay();
            }

            // apakah keluar lebih awal?
            $isLeftEarly = $currentTime->lt($jamKeluar);

            // update data absen
            $absen->check_out_time = $currentTime->format('H:i:s');
            $absen->check_out_latitude = $request->lat;
            $absen->check_out_longitude = $request->lon;
            $absen->check_out_distance = $distance;
            $absen->check_out_face_similarity = 1 - $request->face_distance;

            // jika keluar sebelum waktunya
            if ($isLeftEarly) {
                $absen->status = "Setengah Hari";
            }

            $absen->save();

            return response()->json([
                "status" => "success",
                "message" => "Check-out berhasil",
                "type" => "check-out",
                "absen" => $absen
            ]);
        }
        // =========================
        //  6) SUDAH CHECK OUT
        // =========================
        return response()->json([
            "status" => "error",
            "message" => "Anda sudah check-out hari ini"
        ], 400);
    }

    /**
     * Hitung jarak (meter) menggunakan rumus Haversine
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // meter
    }


    public function edit($id)
    {
        $absen = Absen::with(['karyawan'])->findOrFail($id);

        return view('admin.absen.edit', compact('absen'));
    }

    public function update(Request $request, Absen $absen)
    {
        try {
            // Validasi
               $validated = $request->validate([
                'date' => 'required|date',
                'status' => 'nullable|string',
                'is_late' => 'nullable|in:on,off',
                'check_in_time' => 'nullable',
                'check_in_latitude' => 'nullable',
                'check_in_longitude' => 'nullable',
                'check_out_time' => 'nullable',
                'check_out_latitude' => 'nullable',
                'check_out_longitude' => 'nullable',
            ]);

            $isLate = $request->boolean('is_late');

            // Update model
            $absen->update([
                'date' => $validated['date'],
                'status' => $validated['status'],
                'is_late' =>  $isLate,

                'check_in_time' => $validated['check_in_time'],
                'check_in_latitude' => $validated['check_in_latitude'],
                'check_in_longitude' => $validated['check_in_longitude'],

                'check_out_time' => $validated['check_out_time'],
                'check_out_latitude' => $validated['check_out_latitude'],
                'check_out_longitude' => $validated['check_out_longitude'],
            ]);

            // Redirect sukses
            return redirect()->route('absen.index')
                ->with('success', 'Data absen berhasil diupdate.');

        } catch (\Exception $e) {

            // Log error ke laravel.log
            Log::error('Gagal update absen: ' . $e->getMessage(), [
                'absen_id' => $absen->id,
                'trace' => $e->getTraceAsString()
            ]);

            // Redirect back + tampilkan pesan error
            return redirect()->back()
                ->withErrors(['update_error' => 'Terjadi kesalahan saat update data.'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $absen = Absen::findOrFail($id);
            $absen->delete();

            return redirect()
                ->route('absen.index')
                ->with('success', 'Data absen berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('ERROR absen DELETE: ' . $e->getMessage());
            return redirect()
                ->route('absen.index')
                ->with('error', 'Gagal menghapus absen');
        }
    }

    
    public function izin()
    {
        return view('izin');
    }

    public function storeIzin(Request $request)
    {
        try {
            // Validasi catatan izin
            $validated = $request->validate([
                    'izin' => 'required|in:Izin,Sakit',   // ENUM
                    'catatan' => 'required|string|max:255'
            ]);

            // Ambil user ID yang login
            $userId = Auth::id();

            // Cari karyawan berdasarkan user_id
            $karyawan = Karyawan::where('user_id', $userId)->firstOrFail();

            // Simpan absen
            Absen::create([
                'karyawan_id' => $karyawan->id,
                'status'      => $validated['izin'], // Izin atau Sakit
                'notes'       => $validated['catatan'],
                'date'        => now()->toDateString(),
                'is_late' => false
            ]);

            return redirect()->route('absen')
                ->with('success', 'Izin berhasil dicatat.');

        } catch (\Exception $e) {

            Log::error('Gagal membuat absen izin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Gagal mencatat izin.'])
                ->withInput();
        }
    }

    // Untuk USER BIASA - tanpa parameter
    public function showMine(Request $request)
    {
        // Ambil user ID yang login
        $userId = Auth::id();

        // Cari karyawan berdasarkan user_id
        $karyawan = Karyawan::with('jabatan')
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Ambil bulan dan tahun dari request, default bulan ini
        $monthYear = $request->input('month', now()->format('Y-m'));

        [$year, $month] = explode('-', $monthYear);

        // jadikan integer
        $year = (int) $year;
        $month = (int) $month;

                
        // Ambil data absen untuk bulan tersebut
        $absens = Absen::where('karyawan_id', $karyawan->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        $monthYear = sprintf('%04d-%02d', $year, $month);

        $stats = $this->getStatusPerMonth($year, $month, $karyawan->id);

        return view('riwayat-absen', compact('karyawan', 'absens', 'month', 'year', 'monthYear','stats'));
    }

    // Untuk ADMIN - dengan parameter karyawanId
    public function show(Request $request, $karyawanId)
    {
        // Pastikan hanya admin yang bisa akses
        // Uncomment jika sudah ada role/permission
        // $this->authorize('view-any-absen'); 
        
        $karyawan = Karyawan::with('jabatan')->findOrFail($karyawanId);
        
        // Ambil bulan dan tahun dari request, default bulan ini
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
         
        $monthYear = $request->input('month', now()->format('Y-m'));

        [$year, $month] = explode('-', $monthYear);

        // jadikan integer
        $year = (int) $year;
        $month = (int) $month;


        // Ambil data absen untuk bulan tersebut
        $absens = Absen::where('karyawan_id', $karyawanId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });
        
        $stats = $this->getStatusPerMonth($year, $month, $karyawan->id);
        
        return view('riwayat-absen', compact('karyawan', 'absens', 'month', 'year','monthYear', 'stats'));
    }

    private function getStatusPerMonth($year, $month, $karyawanId)
    {
        $query = Absen::where('karyawan_id', $karyawanId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        $stats = [
            'hadir'         => $query->clone()->where('status', 'Hadir')->count(),
            'sakit'         => $query->clone()->where('status', 'Sakit')->count(),
            'izin'          => $query->clone()->where('status', 'Izin')->count(),
            'alpha'         => $query->clone()->where('status', 'Alpha')->count(),
            'setengah_hari' => $query->clone()->where('status', 'Setengah Hari')->count(),
            'terlambat'     => $query->clone()->where('is_late', true)->count(),
        ];

        return $stats;
    }


}
