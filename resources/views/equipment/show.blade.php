@extends('petugas.layout')

@section('title', 'Detail Barang')

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>{{ $equipment->name }}</h1>
        @if ($equipment->photo_path)
            <img class="lab-detail-photo" src="{{ route('petugas.equipment.photo', $equipment) }}" alt="Foto {{ $equipment->name }}">
        @endif
        <dl>
            <dt>Kategori</dt><dd>{{ $equipment->category }}</dd>
            <dt>Jumlah tersedia</dt><dd>{{ number_format($equipment->available_quantity, 0, ',', '.') }}</dd>
            <dt>Kondisi</dt><dd>{{ $equipment->condition }}</dd>
        </dl>
        <div class="lab-actions">
            <a href="{{ route('petugas.equipment.index') }}">Kembali ke Data Lab</a>
            <a href="{{ route('petugas.equipment.edit', $equipment) }}">Edit Barang</a>
        </div>
    </section>
@endsection
