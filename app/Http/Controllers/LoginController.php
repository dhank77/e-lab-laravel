<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'access' => ['sometimes', 'required', 'in:mahasiswa,petugas'],
            'username' => ['required', 'string', 'alpha_dash:ascii', 'max:255'],
            'password' => ['required', 'string', 'max:4096'],
        ], [
            'access.required' => 'Pilih peran untuk masuk.',
            'access.in' => 'Pilih Mahasiswa atau Petugas Lab.',
            'username.required' => 'Masukkan User Name.',
            'username.alpha_dash' => 'User Name hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.max' => 'User Name terlalu panjang.',
            'password.required' => 'Masukkan Password.',
            'password.string' => 'Masukkan Password yang valid.',
            'password.max' => 'Password terlalu panjang.',
        ]);

        $credentials['username'] = Str::lower($credentials['username']);
        $key = 'login:'.hash('sha256', $credentials['username'].'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'username' => 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.',
            ]);
        }

        $access = $credentials['access'] ?? null;
        unset($credentials['access']);

        if ($access !== null) {
            $credentials['role'] = $access === 'petugas' ? 'admin' : 'mahasiswa';
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'username' => $access === null
                    ? 'User Name atau Password salah.'
                    : 'User Name, Password, atau peran tidak sesuai.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return $this->dashboard($request);
    }

    public function dashboard(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            'mahasiswa' => to_route('mahasiswa.dashboard'),
            'admin' => to_route('petugas.dashboard'),
            default => abort(403),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
