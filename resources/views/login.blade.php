@extends('layout')

@section('title', 'Masuk')

@section('content')
    <div class="login">
        <section class="intro" aria-labelledby="intro-title">
            <span class="tag">PORTAL LABORATORIUM</span>
            <h1 id="intro-title">Data Laboratorium dan Peminjaman</h1>
            <p>Portal bagi mahasiswa dan petugas untuk mengelola peminjaman alat laboratorium.</p>
        </section>
        <section class="panel" aria-labelledby="login-title">
            <h2 id="login-title">Masuk ke E-Lab</h2>
            <p>Pilih akses, lalu masukkan User Name dan Password.</p>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label for="access">Masuk sebagai</label>
                    <select id="access" name="access" required
                        @error('access') aria-invalid="true" aria-describedby="access-error" @enderror>
                        <option value="">Pilih peran</option>
                        <option value="mahasiswa" @selected(old('access') === 'mahasiswa')>Mahasiswa</option>
                        <option value="petugas" @selected(old('access') === 'petugas')>Petugas Lab</option>
                    </select>
                    @error('access') <p class="error" id="access-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="username">User Name</label>
                    <input id="username" name="username" type="text" autocomplete="username" autocapitalize="none" spellcheck="false" maxlength="255"
                        placeholder="Masukkan User Name" value="{{ old('username') }}" required
                        @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
                    @error('username') <p class="error" id="username-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan Password" required
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password') <p class="error" id="password-error" role="alert">{{ $message }}</p> @enderror
                </div>
                <button class="primary" type="submit">Masuk</button>
            </form>
            <a class="secondary guest-link" href="{{ route('guest.catalog') }}">Masuk sebagai Guest</a>
        </section>
    </div>
@endsection
