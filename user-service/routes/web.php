<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->with('error', 'Email atau password salah');
});

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'address' => $request->address,
        'status' => 'active',
    ]);

    return redirect('/login?registered=success');
});

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return view('dashboard', [
        'user' => Auth::user()
    ]);
});

Route::get('/profile', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return view('profile', [
        'user' => Auth::user()
    ]);
});

Route::get('/catalog', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return view('catalog');
});

Route::get('/history', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    try {
        $response = Http::withHeaders([
            'X-Service-Key' => env('SERVICE_KEY'),
        ])->get(env('PEMINJAMAN_SERVICE_URL') . '/api/borrowings/user/' . $user->id);
        $histories = $response->successful() ? $response->json() : [];
    } catch (\Exception $e) {
        $histories = [];
    }

    return view('history', [
        'user' => $user,
        'histories' => $histories
    ]);
});

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
});
