@extends('layouts.app')
@section('title', 'Kelola Peminjaman')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Kelola Peminjaman</h1>
    <p class="text-sm text-slate-500">Setujui, tolak, dan catat pengembalian barang.</p>
</div>
<div class="mb-4 flex flex-wrap items-center justify-between gap-4">
    @include('loans._filter')
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama, NIM, barang…" class="w-64 rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
        <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Cari</button>
    </form>
</div>
<div class="rounded-xl border border-slate-200 bg-white">
    @include('loans._table', ['admin' => true, 'actionView' => 'loans._actions_admin'])
</div>
<div class="mt-4">{{ $loans->links() }}</div>
@endsection
