@extends('petugas.layout')

@section('title', 'Dashboard Petugas')

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>Dashboard Petugas</h1>
        <p>Selamat datang, {{ auth()->user()->name }}.</p>
        <p class="notice">{{ number_format($availableQuantity, 0, ',', '.') }} unit alat tersedia di inventaris.</p>
        @if (session('status'))
            <p class="notice success" role="status">{{ session('status') }}</p>
        @endif
        <a class="secondary guest-link" href="{{ route('petugas.students.create') }}">Daftarkan Mahasiswa</a>
    </section>
@endsection
