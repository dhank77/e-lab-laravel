@extends('mahasiswa.layout')

@section('title', 'Katalog Alat')

@section('student-content')
    <header class="student-heading">
        <div><h1>Katalog Alat</h1><p>Telusuri alat laboratorium dan ajukan peminjaman.</p></div>
        <button class="primary" type="button" data-loan-open>+ Ajukan Peminjaman</button>
    </header>

    <form class="student-panel student-filters" method="GET" action="{{ route('mahasiswa.dashboard') }}">
        <input name="q" aria-label="Cari nama alat" placeholder="Cari nama alat..." value="{{ $search }}">
        @if ($categories->isNotEmpty())
            <select name="category" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach ($categories as $option)
                    <option value="{{ $option }}" @selected($category === $option)>{{ $option }}</option>
                @endforeach
            </select>
        @endif
        <button class="secondary" type="submit">Cari</button>
        <label class="student-available"><input type="checkbox" name="available" value="1" @checked($available)> Hanya alat yang tersedia</label>
    </form>

    <p class="student-count">{{ $items->total() }} alat ditemukan</p>
    <div class="student-grid">
        @forelse ($items as $item)
            <article class="student-panel student-card">
                <div class="student-card-meta">
                    @if ($item->category !== 'Tanpa kategori') <span>{{ $item->category }}</span> @endif
                    <span @class(['lab-condition', 'lab-condition-good' => $item->condition === 'Baik', 'lab-condition-poor' => $item->condition !== 'Baik'])>{{ $item->condition }}</span>
                </div>
                <h2>{{ $item->name }}</h2>
                <div class="student-card-bottom">
                    <p><strong>{{ $item->available_quantity }}</strong> unit tersedia</p>
                    @if ($item->condition === 'Baik' && $item->available_quantity > 0)
                        <button class="primary" type="button" data-loan-open="{{ $item->id }}">Ajukan Pinjam</button>
                    @endif
                </div>
            </article>
        @empty
            <p class="student-panel">Belum ada alat yang cocok dengan pencarian.</p>
        @endforelse
    </div>
    @include('mahasiswa.pagination', ['pages' => $items])
@endsection
