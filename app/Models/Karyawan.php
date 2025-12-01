<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $guarded = ['id'];
    
    public function user(){
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function jabatan(){
        return $this->belongsTo(Jabatan::class, "jabatan_id", "id");
    }
    public function absens(){
        return $this->hasMany(Absen::class);
    }
    public function absenToday()
    {
        return $this->hasOne(Absen::class, 'karyawan_id')->whereDate('date', now());
    }

}
