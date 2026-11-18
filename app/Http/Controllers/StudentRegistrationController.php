<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentRegistrationController extends Controller
{
    public function create(): View
    {
        return view('petugas.student-registration');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['username' => $request->string('username')->trim()->lower()->toString()]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'alpha_dash:ascii', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:3', 'max:72', 'confirmed'],
        ], [
            'name.required' => 'Masukkan nama mahasiswa.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'username.required' => 'Masukkan User Name.',
            'username.alpha_dash' => 'User Name hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.max' => 'User Name maksimal 255 karakter.',
            'username.unique' => 'User Name sudah digunakan.',
            'password.required' => 'Masukkan Password.',
            'password.min' => 'Password minimal 3 karakter.',
            'password.max' => 'Password maksimal 72 karakter.',
            'password.confirmed' => 'Konfirmasi Password tidak sama.',
        ]);

        if (strlen($data['password']) > 72) {
            throw ValidationException::withMessages(['password' => 'Password terlalu panjang.']);
        }

        $student = new User;
        $student->name = $data['name'];
        $student->username = $data['username'];
        $student->email = Str::uuid().'@elab.test';
        $student->password = $data['password'];
        $student->role = 'mahasiswa';

        try {
            $student->save();
        } catch (UniqueConstraintViolationException $exception) {
            if (! User::query()->where('username', $data['username'])->exists()) {
                throw $exception;
            }

            throw ValidationException::withMessages(['username' => 'User Name sudah digunakan.']);
        }

        return to_route('petugas.dashboard')->with('status', 'Akun mahasiswa berhasil dibuat.');
    }
}
