<!DOCTYPE html>
<html>
<head>
    <title>Login - PinjamBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="width: 400px;">
        <h3 class="text-center fw-bold text-primary mb-4">Login Member</h3>
        @if(request('registered') == 'success')
            <div class="alert alert-success">
                Registrasi berhasil. Silakan login.
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Belum punya akun? <a href="/register">Register</a>
        </p>
    </div>
</div>

</body>
</html>
