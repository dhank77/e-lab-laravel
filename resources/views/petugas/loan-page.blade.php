@extends('petugas.layout')

@section('title', $heading)

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>{{ $heading }}</h1>
        <p>{{ $description }}</p>
        <p class="notice">Modul peminjaman belum tersedia.</p>
    </section>
@endsection
