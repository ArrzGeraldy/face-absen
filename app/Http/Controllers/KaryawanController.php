<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // $karyawans = Karyawan::with(['user', 'jabatan'])->paginate(2);
        $karyawans = Karyawan::with(['user', 'jabatan'])
            ->when($request->search, fn($q, $v) => $q->where('nama', 'like', "%$v%"))
            ->when($request->jabatan, fn($q, $v) => $q->where('jabatan_id', $v))
            ->paginate(10);

        $jabatans = Jabatan::all();

        // dd($karyawans);
        return view('admin.karyawan.index', compact('karyawans','jabatans'));
    }

    public function create()
    {
        $jabatans = Jabatan::all(); // atau Jabatan::select('id', 'nama')->get();
        return view('admin.karyawan.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'email' => 'required|email|min:3|max:100|unique:users,email',
            'nip' => 'required|string|min:3|max:50',
            'phone' => 'required|string|max:20',

            'jabatan_id' => 'required|exists:jabatans,id',

            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',

            'alamat' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',

            'password' => 'required|string|min:6',

            'face_descriptors' => 'nullable|string',

            'photo' => 'required|string',
        ]);


        DB::beginTransaction();

        $photoPath = null; // simpan path untuk bisa dihapus jika error

        try {
            // Decode base64 ke file
            $photoData = $validated['photo'];
            $image = str_replace('data:image/png;base64,', '', $photoData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'photo_' . time() . '.png';
            $photoPath = '/uploads/' . $imageName;

            File::put(public_path($photoPath), base64_decode($image));

            // Simpan data user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Simpan face descriptors (jika ada)
            $faceDescriptorsJson = isset($validated['face_descriptors'])
                ? json_encode($validated['face_descriptors'])
                : null;

            // Simpan data karyawan
            Karyawan::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'phone' => $validated['phone'],
                'jabatan_id' => $validated['jabatan_id'],
                'face_descriptors' => $faceDescriptorsJson,
                'photo' => $photoPath,
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
            ]);

            DB::commit();

            return redirect()
                ->route('karyawan.index')
                ->with('success', 'Data karyawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika sudah dibuat
            if ($photoPath && File::exists(public_path($photoPath))) {
                File::delete(public_path($photoPath));
            }

            Log::error('ERROR KARYAWAN STORE: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal registrasi karyawan.');
        }
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with('user', 'jabatan')->findOrFail($id);
        $jabatans = Jabatan::all();

        return view('admin.karyawan.edit', compact('karyawan', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'email' => 'required|email|min:3|max:100|unique:users,email,' . $karyawan->user->id,
            'nip' => 'required|string|min:3|max:50',
            'phone' => 'required|string|max:20',
            'jabatan_id' => 'required|exists:jabatans,id',
            'face_descriptors' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $isActive = $request->boolean('is_active');


        DB::beginTransaction();
        $photoPath = null;

        try {
            $user = $karyawan->user;

            // Update foto jika ada kiriman baru (base64)
            if (!empty($validated['photo']) && str_starts_with($validated['photo'], 'data:image')) {
                // Hapus foto lama jika ada
                if ($karyawan->photo && File::exists(public_path($karyawan->photo))) {
                    File::delete(public_path($karyawan->photo));
                }

                $photoData = $validated['photo'];
                $image = str_replace('data:image/png;base64,', '', $photoData);
                $image = str_replace(' ', '+', $image);
                $imageName = 'photo_' . time() . '.png';
                $photoPath = 'uploads/' . $imageName;
                File::put(public_path($photoPath), base64_decode($image));
            } else {
                $photoPath = $karyawan->photo; // gunakan foto lama
            }

            // Update user
            $user->update([
                'email' => $validated['email'] == $user->email ? $user->email : $validated['email'],
                'password' => !empty($validated['password'])
                    ? Hash::make($validated['password'])
                    : $user->password,
            ]);

            // Simpan face descriptors (jika ada)
            $faceDescriptorsJson = isset($validated['face_descriptors'])
                ? json_encode($validated['face_descriptors'])
                : $karyawan->face_descriptors;

            // Update karyawan
            $karyawan->update([
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'phone' => $validated['phone'],
                'jabatan_id' => $validated['jabatan_id'],
                'face_descriptors' => $faceDescriptorsJson,
                'photo' => $photoPath,
                'is_active' => $isActive
            ]);

            DB::commit();

            return redirect()
                ->route('karyawan.index')
                ->with('success', 'Data karyawan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika baru dibuat dan gagal
            if ($photoPath && File::exists(public_path($photoPath)) && $photoPath !== $karyawan->photo) {
                File::delete(public_path($photoPath));
            }

            Log::error('ERROR KARYAWAN UPDATE: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data karyawan.');
        }
    }


    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus foto dari folder
            if (File::exists(public_path($karyawan->photo))) {
                File::delete(public_path($karyawan->photo));
            }

            $userId = $karyawan->user->id;
            $karyawan->delete();

            $user = User::findOrFail($userId);

            $user->delete();


            DB::commit();

            return redirect()
                ->route('karyawan.index')
                ->with('success', 'Data karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR KARYAWAN DELETE: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus karyawan.');
        }
    }

    public function showChangePasswordForm(User $user)
    {
        return view('admin.karyawan.change-pass', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        try {
            $request->validate([
                'password' => 'required|min:6',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()
                ->route('karyawan.index')
                ->with('success', 'Password berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error("Gagal update password karyawan: " . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat update password.'])
                ->withInput();
        }
    }



}
