<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'Data profil berhasil diambil',
            'data' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }

    public function validateMember($id)
    {
        $user = User::find($id);

        if (!$user || $user->status !== 'active') {
            return response()->json([
                'valid' => false,
                'message' => 'Member tidak valid atau tidak aktif',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Member valid',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ],
        ]);
    }

    public function borrowingHistory($id)
    {
        $response = Http::get(env('PEMINJAMAN_SERVICE_URL') . "/api/borrowings/user/$id");

        return response()->json([
            'message' => 'Histori peminjaman berhasil diambil dari Peminjaman Service',
            'data' => $response->json(),
        ]);
    }
}

