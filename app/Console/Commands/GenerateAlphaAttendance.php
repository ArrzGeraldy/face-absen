<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Absen;
use Carbon\Carbon;

class GenerateAlphaAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate alpha status for employees who did not attend';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        
        // Get semua karyawan
        $karyawans = Karyawan::where('is_active', true)->get();
        
        $alphaCount = 0;
        
        foreach ($karyawans as $karyawan) {
            // Cek apakah sudah ada record absensi kemarin
            $hasAttendance = Absen::where('karyawan_id', $karyawan->id)
                ->whereDate('date', $yesterday)
                ->exists();
            
            // Jika tidak ada, buat record alpha
            if (!$hasAttendance) {
                Absen::create([
                    'karyawan_id' => $karyawan->id,
                    'date' => $yesterday,
                    'check_in_time' => null,
                    'check_in_latitude' => null,
                    'check_in_longitude' => null,
                    'check_in_distance' => null,
                    'check_in_face_similarity' => null,
                    'check_out_time' => null,
                    'check_out_latitude' => null,
                    'check_out_longitude' => null,
                    'check_out_distance' => null,
                    'check_out_face_similarity' => null,
                    'is_late' => false,
                    'status' => 'Alpha',
                    'notes' => 'Auto-generated: Tidak ada absensi',
                ]);
                
                $alphaCount++;
                $this->info("✓ Alpha created for: {$karyawan->nama}");
            }
        }
        
        $this->info("─────────────────────────────────");
        $this->info("✓ Alpha generation completed!");
        $this->info("✓ Total Alpha: {$alphaCount}");
        $this->info("✓ Date: {$yesterday}");
        
        return Command::SUCCESS;
    }
}
