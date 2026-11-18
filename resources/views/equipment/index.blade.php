@extends('petugas.layout')

@section('title', 'Data Lab')

@section('petugas-content')
    <div class="lab-inventory">
        <header class="lab-heading">
            <h1>Data Lab</h1>
            <p>Kelola inventaris dan pantau stok peralatan laboratorium.</p>
        </header>

        <details class="lab-add" @if ($errors->any() && ! $errors->has('equipment')) open @endif>
            <summary class="primary">+ Tambah Barang</summary>
            <section class="panel dashboard inventory-panel">
                <h2>Tambah Alat</h2>
                @include('equipment.form', ['action' => route('petugas.equipment.store')])
            </section>
        </details>

        @if (session('status'))
            <p class="notice success" role="status">{{ session('status') }}</p>
        @endif

        @error('equipment')
            <p class="notice error" role="alert">{{ $message }}</p>
        @enderror
        <dl class="lab-stats">
            <div><dt>Jenis barang</dt><dd>{{ number_format($equipmentCount, 0, ',', '.') }}</dd></div>
            <div><dt>Unit tersedia</dt><dd>{{ number_format($availableQuantity, 0, ',', '.') }}</dd></div>
            <div><dt>Pengajuan menunggu</dt><dd>{{ $pendingLoanCount }}</dd></div>
            <div><dt>Unit rusak</dt><dd>{{ number_format($damagedQuantity, 0, ',', '.') }}</dd></div>
        </dl>

        <section class="lab-table-panel" aria-label="Daftar barang">
            <form class="lab-filters" method="GET" action="{{ route('petugas.equipment.index') }}">
                <input name="q" aria-label="Cari nama barang" placeholder="Cari nama barang..." value="{{ $search }}">
                <select name="condition" aria-label="Kondisi">
                    <option value="">Semua kondisi</option>
                    @foreach ($conditions as $option)
                        <option value="{{ $option }}" @selected($condition === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <button class="secondary" type="submit">Filter</button>
            </form>
            <div class="lab-table-scroll" tabindex="0" role="region" aria-label="Tabel barang">
                <table class="lab-table">
                    <thead>
                        <tr><th scope="col">No.</th><th scope="col">Nama Barang</th><th scope="col">Jumlah Tersedia</th><th scope="col">Kondisi</th><th scope="col">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($equipmentList as $equipment)
                            <tr>
                                <td>{{ $equipmentList->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $equipment->name }}</strong></td>
                                <td>{{ number_format($equipment->available_quantity, 0, ',', '.') }} unit tersedia</td>
                                <td>
                                    <span @class(['lab-condition', 'lab-condition-good' => $equipment->condition === 'Baik', 'lab-condition-poor' => $equipment->condition === 'Buruk'])>
                                        {{ $equipment->condition }}
                                    </span>
                                </td>
                                <td>
                                    <div class="lab-actions">
                                        <a href="{{ route('petugas.equipment.show', $equipment) }}" class="lab-action-icon" aria-label="Lihat barang" title="Lihat barang"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></a>
                                        <a href="{{ route('petugas.equipment.edit', $equipment) }}" class="lab-action-icon" aria-label="Edit barang" title="Edit barang"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m14 5 5 5M4 20l4-.8L20 7l-3-3L5 16Z"/></svg></a>
                                        <form method="POST" action="{{ route('petugas.equipment.destroy', $equipment) }}" onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="lab-delete" type="submit" aria-label="Hapus barang" title="Hapus barang"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7"/></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Belum ada barang yang ditampilkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($equipmentList->previousPageUrl() || $equipmentList->nextPageUrl())
                <nav class="lab-pages" aria-label="Halaman inventaris">
                    @if ($equipmentList->previousPageUrl())
                        <a href="{{ $equipmentList->previousPageUrl() }}">Sebelumnya</a>
                    @endif
                    @if ($equipmentList->nextPageUrl())
                        <a href="{{ $equipmentList->nextPageUrl() }}">Berikutnya</a>
                    @endif
                </nav>
            @endif
        </section>
    </div>
@endsection
