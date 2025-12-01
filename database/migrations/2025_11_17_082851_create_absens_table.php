<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('restrict');
            $table->date('date'); // Tanggal absensi
            
            // Check In
            $table->time('check_in_time')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->decimal('check_in_distance', 8, 2)->nullable(); // Jarak dari kantor (meter)
            $table->decimal('check_in_face_similarity', 3, 2)->nullable(); // 0.00-1.00
            
            // Check Out
            $table->time('check_out_time')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->decimal('check_out_distance', 8, 2)->nullable();
            $table->decimal('check_out_face_similarity', 3, 2)->nullable();
            
            $table->boolean('is_late');

            // Status & Notes
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha', 'Setengah Hari'])->default('Hadir');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa
            $table->unique(['karyawan_id', 'date']); // Satu karyawan hanya bisa absen 1x per hari
            $table->index('date');
            $table->index(['karyawan_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
