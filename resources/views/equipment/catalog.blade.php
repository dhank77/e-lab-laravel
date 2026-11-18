@extends('layout')

@section('title', 'Katalog Alat')

@section('content')
    <section class="panel dashboard inventory-panel" aria-labelledby="catalog-title">
        <span class="tag">GUEST</span>
        <h1 id="catalog-title">Katalog Alat Laboratorium</h1>
        <p>Lihat kategori, kondisi, dan jumlah alat yang tersedia.</p>
        <ul class="inventory-list">
            @forelse ($equipmentList as $equipment)
                <li>
                    <div>
                        <strong>{{ $equipment->name }}</strong>
                        <p>{{ $equipment->category }} &middot; {{ $equipment->condition }}</p>
                        <p>{{ number_format($equipment->available_quantity, 0, ',', '.') }} unit tersedia</p>
                    </div>
                </li>
            @empty
                <li><p>Belum ada data alat di katalog.</p></li>
            @endforelse
        </ul>
        @if ($equipmentList->hasPages())
            <nav class="catalog-pagination" aria-label="Halaman katalog">
                @if ($equipmentList->previousPageUrl())
                    <a href="{{ $equipmentList->previousPageUrl() }}">Sebelumnya</a>
                @endif
                @if ($equipmentList->nextPageUrl())
                    <a href="{{ $equipmentList->nextPageUrl() }}">Berikutnya</a>
                @endif
            </nav>
        @endif
        <a class="secondary guest-link" href="{{ route('login') }}">Kembali ke Login</a>
    </section>
@endsection
