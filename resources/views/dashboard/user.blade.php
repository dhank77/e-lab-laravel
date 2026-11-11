@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Halo, {{ auth()->user()->name }} 👋</h1>
        <p class="text-sm text-slate-500">Mau pinjam alat apa hari ini?</p>
    </div>
    <a href="{{ route('labs.index') }}" class="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Pinjam Barang</a>
</div>

<div class="grid gap-4 sm:grid-cols-3">
    @foreach ([['Menunggu Persetujuan', $stats['pending'], 'pending'], ['Sedang Dipinjam', $stats['active'], 'approved'], ['Sudah Dikembalikan', $stats['returned'], 'returned']] as [$label, $value, $status])
        <a href="{{ route('loans.index', ['status' => $status]) }}" class="rounded-xl border border-slate-200 bg-white p-5 hover:border-brand-300">
            <p class="text-sm text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p>
        </a>
    @endforeach
</div>

<div class="mt-8 grid gap-8 lg:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white lg:col-span-2">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Peminjaman Terakhir</h2>
            <a href="{{ route('loans.index') }}" class="text-sm font-semibold text-brand-600 hover:underline">Lihat semua</a>
        </div>
        @include('loans._table', ['loans' => $latestLoans, 'admin' => false])
    </div>
    <div class="rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-semibold text-slate-900">Laboratorium</h2></div>
        <ul class="divide-y divide-slate-100">
            @foreach ($labs as $lab)
                <li>
                    <a href="{{ route('labs.show', $lab) }}" class="flex items-center justify-between px-5 py-3 hover:bg-slate-50">
                        <span>
                            <span class="block text-sm font-medium text-slate-900">{{ $lab->name }}</span>
                            <span class="text-xs text-slate-500">{{ $lab->location }}</span>
                        </span>
                        <span class="text-xs text-slate-500">{{ $lab->items_count }} barang</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
