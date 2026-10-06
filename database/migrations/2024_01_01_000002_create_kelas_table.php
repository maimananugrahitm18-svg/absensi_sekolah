<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g. "X IPA 1"
            $table->string('tingkat'); // X, XI, XII
            $table->string('jurusan')->nullable(); // IPA, IPS, dll
            $table->string('wali_kelas')->nullable();
            $table->integer('kapasitas')->default(30);
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
