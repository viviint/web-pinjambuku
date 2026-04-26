<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - PinjamBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">PinjamBuku</a>

        <div>
            <a href="/profile" class="btn btn-outline-primary btn-sm">Profil</a>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Dashboard Member</h2>
            <p class="text-muted">Selamat datang di User Service PinjamBuku.</p>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-primary">Catalog Buku</h5>
                <p class="text-muted">Lihat daftar buku dari Katalog Service.</p>
                <a href="/catalog" class="btn btn-primary w-100">Lihat Catalog</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-success">Status Akun</h5>

                @if($user->status == 'active')
                    <p class="text-muted">Akun member aktif dan dapat melakukan peminjaman buku.</p>
                    <span class="badge bg-success p-2">ACTIVE</span>
                @else
                    <p class="text-muted">Akun member tidak aktif dan tidak dapat melakukan peminjaman buku.</p>
                    <span class="badge bg-danger p-2">INACTIVE</span>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold text-warning">Histori Peminjaman</h5>
                <p class="text-muted">Lihat riwayat peminjaman dari Peminjaman Service.</p>
                <a href="/history" class="btn btn-warning w-100">Lihat Histori</a>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-5 p-4">
        <h5 class="fw-bold">Integrasi Service</h5>
        <p class="text-muted mb-0">
            User Service berperan sebagai provider untuk validasi member dan sebagai consumer
            untuk mengambil histori peminjaman dari Peminjaman Service.
        </p>
    </div>
</div>

</body>
</html>

