<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{

    public function index()
    {
        $today = Carbon::today();
        
        // Total karyawan aktif
        $totalKaryawan = Karyawan::where('is_active', true)->count();
        
        // Statistik absensi hari ini
        $stats = [
            'hadir' => Absen::whereDate('date', $today)
                ->where('status', 'Hadir')
                ->count(),
                
            'sakit' => Absen::whereDate('date', $today)
                ->where('status', 'Sakit')
                ->count(),
                
            'izin' => Absen::whereDate('date', $today)
                ->where('status', 'Izin')
                ->count(),
                
            'alpha' => Absen::whereDate('date', $today)
                ->where('status', 'Alpha')
                ->count(),
                
            'setengah_hari' => Absen::whereDate('date', $today)
                ->where('status', 'Setengah Hari')
                ->count(),
                
            'terlambat' => Absen::whereDate('date', $today)
                ->where('is_late', true)
                ->count(),
        ];
        
        // Hitung yang belum absen
        $sudahAbsen = Absen::whereDate('date', $today)->pluck('karyawan_id');
        $stats['belum_absen'] = Karyawan::where('is_active', true)
            ->whereNotIn('id', $sudahAbsen)
            ->count();

        $absens = Absen::with([
            'karyawan.jabatan'
        ])
        ->whereDate('date', $today)
        ->latest()
        ->get();
        
        
        return view('admin.dashboard', compact('stats', 'totalKaryawan','absens'));

    }
}
