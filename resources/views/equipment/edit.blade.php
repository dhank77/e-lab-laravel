@extends('petugas.layout')

@section('title', 'Edit Barang')

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>Edit Barang</h1>
        <a href="{{ route('petugas.equipment.index') }}">Kembali ke Data Lab</a>
        @include('equipment.form', ['action' => route('petugas.equipment.update', $equipment)])
    </section>
@endsection
