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
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7); // Contoh: -6.2087634
            $table->decimal('longitude', 10, 7); // Contoh: 106.8456035
            $table->integer('radius')->default(50)->comment('Radius dalam meter');
            $table->time('jam_masuk')->default('08:00:00');
            $table->time('jam_pulang')->default('17:00:00');
            $table->integer('toleransi_terlambat')->default(15)->comment('Toleransi keterlambatan dalam menit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};
