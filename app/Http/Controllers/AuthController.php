<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Periksa status akun pengguna.
            if (!$user || $user->status !== 'aktif') {
                Auth::logout();

                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }

            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /**
     * Proses login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Periksa apakah pengguna ditemukan.
        if (!$user) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Pengguna tidak ditemukan.',
            ])->onlyInput('email');
        }

        // Tolak login jika akun tidak aktif.
        if ($user->status !== 'aktif') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->onlyInput('email');
        }

        // Validasi role pengguna.
        if (!in_array($user->role, ['admin', 'petugas'])) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Role pengguna tidak valid.',
            ])->onlyInput('email');
        }

        return $this->redirectByRole();
    }

    /**
     * Mengarahkan pengguna sesuai role.
     */
    private function redirectByRole()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Periksa kembali status akun.
        if ($user->status !== 'aktif') {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }

        Auth::logout();

        return redirect()->route('login')->withErrors([
            'email' => 'Role pengguna tidak valid.',
        ]);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
