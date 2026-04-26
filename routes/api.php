<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users/{id}/validate', [UserController::class, 'validateMember']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::get('/users/{id}/borrowings', [UserController::class, 'borrowingHistory']);
});

Route::get('/users/{id}/validate', function ($id) {
    $user = \App\Models\User::find($id);

    if (!$user) {
        return response()->json([
            'valid' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    if ($user->status !== 'active') {
        return response()->json([
            'valid' => false,
            'message' => 'User tidak aktif'
        ], 403);
    }

    return response()->json([
        'valid' => true,
        'message' => 'User aktif',
        'data' => $user
    ]);
});
