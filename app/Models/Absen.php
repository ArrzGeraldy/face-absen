<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    //
    protected $guarded = ['id'];

    // ✅ Tambahkan ini
    protected $casts = [
        'date' => 'date',  // Otomatis convert ke Carbon
        'is_late' => 'boolean',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
    ];

    public function karyawan(){
        return $this->belongsTo(Karyawan::class, "karyawan_id","id");
    }
}
