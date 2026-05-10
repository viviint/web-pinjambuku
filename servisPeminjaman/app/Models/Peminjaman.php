<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    protected $fillable = ['id_anggota', 'id_buku', 'tanggal_pinjam', 'batas_waktu', 'tanggal_kembali', 'status_pinjaman', 'total_denda'];
}