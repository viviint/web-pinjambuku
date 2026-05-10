<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'message' => 'Registrasi berhasil',
        'data' => $user
    ], 201);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ], 401);
    }

    return response()->json([
        'message' => 'Login berhasil',
        'token_type' => 'bearer',
        'access_token' => $token,
        'user' => auth('api')->user()
    ]);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', function () {
        return response()->json([
            'message' => 'Data profil berhasil diambil',
            'data' => auth('api')->user()
        ]);
    });

    Route::post('/logout', function () {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    });
});

Route::get('/users/{id}/validate', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'valid' => false,
            'message' => 'Member tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'valid' => true,
        'message' => 'Member aktif dan boleh melakukan peminjaman',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]
    ]);
});

