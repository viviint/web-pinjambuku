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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->integer('id_anggota');
            $table->string('id_buku');
            $table->date('tanggal_pinjam');
            $table->date('batas_waktu');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status_pinjaman')->default('dipinjam');
            $table->integer('total_denda')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
