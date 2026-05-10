<!DOCTYPE html>
<html>
<head>
    <title>Histori Peminjaman - PinjamBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/dashboard">PinjamBuku</a>

        <div>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <a href="/dashboard" class="btn btn-outline-secondary mb-4">← Kembali</a>
    <h2 class="fw-bold mb-2">Histori Peminjaman</h2>
    <p class="text-muted mb-4">
        Data ini diambil dari Peminjaman Service untuk member:
        <strong>{{ $user->name }}</strong>
    </p>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        @if(count($histories) > 0)
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr>
                            <td>{{ $history['book_title'] ?? '-' }}</td>
                            <td>{{ $history['borrow_date'] ?? '-' }}</td>
                            <td>{{ $history['return_date'] ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $history['status'] ?? 'Dipinjam' }}
                                </span>
                            </td>
                            <td>Rp {{ $history['fine'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning mb-0">
                Histori peminjaman belum tersedia atau Peminjaman Service belum aktif.
            </div>
        @endif
    </div>
</div>

</body>
</html>
