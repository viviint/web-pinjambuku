<!DOCTYPE html>
<html>
<head>
    <title>Register - PinjamBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="width: 450px;">
        <h3 class="text-center fw-bold text-primary mb-4">Register Member</h3>

        <form action="/register" method="POST">
             @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor HP">
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-control" rows="3" placeholder="Masukkan alamat"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Sudah punya akun? <a href="/login">Login</a>
        </p>
    </div>
</div>

</body>
</html>
