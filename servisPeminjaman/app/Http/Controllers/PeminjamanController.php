<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|integer',
            'id_buku' => 'required|string',
        ]);

        $userServiceUrl = env('USER_SERVICE_URL');
        $bookServiceUrl = env('BOOK_SERVICE_URL');
        $serviceKey = env('SERVICE_KEY');

        try {
            $responseUser = Http::get("{$userServiceUrl}/api/users/{$request->id_anggota}/validate");
            if (!$responseUser->successful() || $responseUser->json('valid') !== true) {
                return response()->json([
                    'pesan' => 'Gagal: ' . ($responseUser->json('message') ?? 'Member tidak valid/aktif')
                ], 403);
            }

            $responseBook = Http::withHeaders(['X-Service-Key' => $serviceKey])
                                ->get("{$bookServiceUrl}/api/internal/books/{$request->id_buku}/stock");

            if (!$responseBook->successful()) {
                return response()->json(['pesan' => 'Gagal: Buku tidak ditemukan di Katalog'], 404);
            }

            $bookData = $responseBook->json('data');
            if ($bookData['stock_available'] < 1) {
                return response()->json(['pesan' => 'Gagal: Stok buku sedang kosong'], 400);
            }

            $peminjaman = Peminjaman::create([
                'id_anggota' => $request->id_anggota,
                'id_buku'    => $request->id_buku,
                'tanggal_pinjam' => Carbon::now()->toDateString(),
                'batas_waktu' => Carbon::now()->addDays(7)->toDateString(),
                'status_pinjaman' => 'dipinjam',
                'total_denda' => 0
            ]);

            $reserveStock = Http::withHeaders(['X-Service-Key' => $serviceKey])
                ->patch("{$bookServiceUrl}/api/internal/books/{$request->id_buku}/stock", [
                    'action' => 'reserve',
                    'quantity' => 1
                ]);
            if (!$reserveStock->successful()) {
                $peminjaman->delete();
                return response()->json(['pesan' => 'Gagal memotong stok buku, transaksi dibatalkan.'], 500);
            }

            SendNotificationJob::dispatch(
                (int) $request->id_anggota,
                "Peminjaman buku {$request->id_buku} berhasil dibuat."
            );

            return response()->json([
                'pesan' => 'Berhasil! Buku telah dipinjam.',
                'data' => $peminjaman
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'pesan' => 'UPS! Terjadi kesalahan sistem atau server layanan lain sedang mati.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function kembali($id_transaksi)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id_transaksi);

            if ($peminjaman->status_pinjaman === 'selesai') {
                return response()->json(['pesan' => 'Buku ini sudah dikembalikan sebelumnya.'], 400);
            }
            $tanggalKembali = Carbon::now();
            $batasWaktu = Carbon::parse($peminjaman->batas_waktu);
            
            $totalDenda = 0;
            $keteranganDenda = "Tepat waktu. Terima kasih!";

            if ($tanggalKembali->greaterThan($batasWaktu)) {
                $telatHari = $tanggalKembali->diffInDays($batasWaktu);
                $tarifDenda = 5000;
                
                $totalDenda = $telatHari * $tarifDenda;

                $formatRupiah = number_format($totalDenda, 0, ',', '.');
                $keteranganDenda = "Terlambat {$telatHari} hari. Kamu dikenakan denda sebesar Rp {$formatRupiah}.";
            }

            $peminjaman->update([
                'tanggal_kembali' => $tanggalKembali->toDateString(),
                'status_pinjaman' => 'selesai',
                'total_denda' => $totalDenda
            ]);
            Http::withHeaders(['X-Service-Key' => env('SERVICE_KEY')])
                ->patch(env('BOOK_SERVICE_URL')."/api/internal/books/{$peminjaman->id_buku}/stock", [
                    'action' => 'release',
                    'quantity' => 1
                ]);

            SendNotificationJob::dispatch(
                (int) $peminjaman->id_anggota,
                "Pengembalian buku {$peminjaman->id_buku} berhasil diproses. {$keteranganDenda}"
            );

            return response()->json([
                'pesan' => 'Buku berhasil dikembalikan.',
                'keterangan_denda' => $keteranganDenda,
                'total_denda_angka' => $totalDenda,
                'data' => $peminjaman
            ], 200);

        }
            catch (Exception $e) {
                return response()->json(['pesan' => 'UPS! Terjadi kesalahan.', 'error' => $e->getMessage()], 500);
            }
    }

    public function historyPerUser($id_anggota)
    {
        $riwayat = Peminjaman::where('id_anggota', $id_anggota)->get();

        return response()->json([
            'pesan' => 'Riwayat peminjaman berhasil diambil',
            'data' => $riwayat
        ], 200);
    }
}
