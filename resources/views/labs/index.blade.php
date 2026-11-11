@extends('layouts.app')
@section('title', 'Data Lab')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Data Laboratorium</h1>
        <p class="text-sm text-slate-500">Pilih lab untuk melihat barang dan meminjamnya.</p>
    </div>
    <div class="flex gap-2">
        <form method="GET"><input name="q" value="{{ request('q') }}" placeholder="Cari lab…" class="w-56 rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500"></form>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('labs.create') }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">+ Tambah Lab</a>
        @endif
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($labs as $lab)
        <a href="{{ route('labs.show', $lab) }}" class="group rounded-xl border border-slate-200 bg-white p-6 transition hover:border-brand-300 hover:shadow-sm">
            <span class="rounded-md bg-brand-50 px-2 py-1 text-xs font-semibold text-brand-700">{{ $lab->code }}</span>
            <h2 class="mt-3 text-lg font-semibold text-slate-900 group-hover:text-brand-700">{{ $lab->name }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $lab->location ?? 'Lokasi belum diisi' }}</p>
            <div class="mt-5 flex gap-6 border-t border-slate-100 pt-4 text-sm">
                <div><p class="text-slate-500">Jenis barang</p><p class="font-semibold">{{ $lab->items_count }}</p></div>
                <div><p class="text-slate-500">Total unit</p><p class="font-semibold">{{ $lab->items_sum_stock ?? 0 }}</p></div>
            </div>
        </a>
    @empty
        <p class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">Belum ada data lab.</p>
    @endforelse
</div>
<div class="mt-6">{{ $labs->links() }}</div>
@endsection
