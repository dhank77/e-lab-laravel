@extends('petugas.layout')

@section('title', 'Daftarkan Mahasiswa')

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>Daftarkan Mahasiswa</h1>
        <form method="POST" action="{{ route('petugas.students.store') }}">
            @csrf
            <div class="field">
                <label for="name">Nama lengkap</label>
                <input id="name" name="name" maxlength="255" value="{{ old('name') }}" required>
                @error('name') <p class="error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="username">User Name</label>
                <input id="username" name="username" maxlength="255" value="{{ old('username') }}" required>
                @error('username') <p class="error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" minlength="3" maxlength="72" autocomplete="new-password" required>
                @error('password') <p class="error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" maxlength="72" autocomplete="new-password" required>
            </div>
            <div class="student-actions">
                <a class="secondary guest-link" href="{{ route('petugas.dashboard') }}">Kembali</a>
                <button class="primary" type="submit">Buat Akun</button>
            </div>
        </form>
    </section>
@endsection
