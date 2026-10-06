<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->role . '.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan atau telah dinonaktifkan.'])->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route(Auth::user()->role . '.dashboard')
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil keluar dari sistem.');
    }

    /**
     * Demo 1-Klik Langsung Masuk:
     * Pengunjung tinggal klik peran (Admin, Guru, atau Siswa) dan langsung masuk ke dashboard
     * tanpa perlu mengetikkan username atau password sama sekali.
     */
    public function demoLogin(Request $request)
    {
        $roleMap = [
            'admin' => 'admin@gmail.com',
            'guru'  => 'guru@gmail.com',
            'siswa' => 'siswa@gmail.com',
        ];

        $role  = strtolower($request->input('role', ''));
        $email = strtolower($request->input('email', ''));

        // Tentukan target email demo
        if ($role && isset($roleMap[$role])) {
            $targetEmail = $roleMap[$role];
        } elseif ($email && in_array($email, array_values($roleMap))) {
            $targetEmail = $email;
        } else {
            return redirect()->route('login')->with('error', 'Pilihan akun demo tidak valid.');
        }

        // Cari user demo
        $user = User::where('email', $targetEmail)->first();
        if (!$user || !$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun demo tidak ditemukan atau nonaktif.');
        }

        // Logout sesi sebelumnya jika ada
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Langsung autentikasi (1-klik instan masuk)
        Auth::login($user, true);
        $request->session()->regenerate();

        $roleTitle = ucfirst($user->role);
        return redirect()->route($user->role . '.dashboard')
            ->with('success', "Selamat datang di Mode Demo sebagai {$roleTitle} ({$user->name})!");
    }
}
