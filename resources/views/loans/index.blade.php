@extends('layouts.app')
@section('title', 'Peminjaman Saya')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Peminjaman Saya</h1>
        <p class="text-sm text-slate-500">Riwayat dan status pengajuan peminjaman barang.</p>
    </div>
    <a href="{{ route('labs.index') }}" class="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">+ Pinjam Barang</a>
</div>
<div class="mb-4">@include('loans._filter')</div>
<div class="rounded-xl border border-slate-200 bg-white">
    @include('loans._table', ['admin' => false, 'actionView' => 'loans._actions_user'])
</div>
<div class="mt-4">{{ $loans->links() }}</div>
@endsection
