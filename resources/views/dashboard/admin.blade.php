@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
    <p class="text-sm text-slate-500">Ringkasan laboratorium dan peminjaman.</p>
</div>

<div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
    @foreach ([['Laboratorium', $stats['labs'], route('labs.index')], ['Total Unit Barang', $stats['items'], route('items.index')], ['Mahasiswa', $stats['users'], null], ['Menunggu Persetujuan', $stats['pending'], route('admin.loans.index', ['status' => 'pending'])], ['Sedang Dipinjam', $stats['active'], route('admin.loans.index', ['status' => 'approved'])]] as [$label, $value, $link])
        <a @if($link) href="{{ $link }}" @endif class="rounded-xl border border-slate-200 bg-white p-5 {{ $link ? 'hover:border-brand-300' : '' }}">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($value) }}</p>
        </a>
    @endforeach
</div>

<div class="mt-8 rounded-xl border border-slate-200 bg-white">
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
        <h2 class="font-semibold text-slate-900">Peminjaman Terbaru</h2>
        <a href="{{ route('admin.loans.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat semua</a>
    </div>
    @include('loans._table', ['loans' => $latestLoans, 'admin' => true])
</div>
@endsection
