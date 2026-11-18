@extends('layout')

@section('title', 'Dashboard '.ucfirst($role))

@section('header')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="secondary" type="submit">Keluar</button>
    </form>
@endsection

@section('content')
    <section class="panel dashboard">
        <span class="tag">{{ strtoupper($role) }}</span>
        <h1>Dashboard {{ ucfirst($role) }}</h1>
        <p>Selamat datang, {{ auth()->user()->name }}.</p>
        @if ($role === 'mahasiswa')
            <p class="notice">Peminjaman aktif dan status pengajuan akan tampil setelah modul peminjaman tersedia.</p>
        @endif
    </section>
@endsection
