<!DOCTYPE html>
<html>
<head>
    <title>Profil - PinjamBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/dashboard">PinjamBuku</a>

        <div>
            <a href="/dashboard" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <a href="/login" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="card border-0 shadow-lg rounded-4 p-4 mx-auto" style="max-width: 600px;">
        <h3 class="fw-bold text-primary mb-4 text-center">Profil Member</h3>

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $user->name }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}">
        </div>

        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" class="form-control" value="{{ $user->phone }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" rows="3">{{ $user->address }}</textarea>
        </div>

        <button class="btn btn-primary w-100">Update Profil</button>
    </div>
</div>

</body>
</html>

