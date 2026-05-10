<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $kunciYangDikirim = $request->header('X-Service-Key');
        $kunciAsli = env('SERVICE_KEY');

        if (empty($kunciAsli) || $kunciYangDikirim !== $kunciAsli) {
            return response()->json([
                'pesan' => 'Akses Ditolak: Kunci Servis (Service Key) tidak valid atau hilang.'
            ], 403);
        }
        return $next($request);
    }
}