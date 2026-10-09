<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses percobaan login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nama_pengguna' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nama_pengguna.required' => 'Nama pengguna wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt(['nama_pengguna' => $credentials['nama_pengguna'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user())
                ->with('success', 'Selamat datang kembali, '.Auth::user()->nama_pengguna.'!');
        }

        return back()
            ->withInput($request->only('nama_pengguna'))
            ->withErrors([
                'nama_pengguna' => 'Nama pengguna atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Redirect pengguna ke dashboard sesuai perannya.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        return match ($user->peran) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'kasir' => redirect()->intended(route('kasir.dashboard')),
            'pemilik' => redirect()->intended(route('pemilik.dashboard')),
            default => redirect()->route('login'),
        };
    }
}
