@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Data Barang</h1>
        <p class="text-sm text-slate-500">Inventaris seluruh laboratorium.</p>
    </div>
    <a href="{{ route('items.create') }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">+ Tambah Barang</a>
</div>
<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama / kode…" class="w-56 rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
    <select name="lab" class="rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300">
        <option value="">Semua lab</option>
        @foreach ($labs as $lab)<option value="{{ $lab->id }}" @selected(request('lab') == $lab->id)>{{ $lab->name }}</option>@endforeach
    </select>
    <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Filter</button>
</form>
<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Lab</th><th class="px-5 py-3">Kondisi</th><th class="px-5 py-3">Tersedia</th><th class="px-5 py-3 text-right">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($items as $item)
                <tr>
                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-500">{{ $item->code }}</td>
                    <td class="px-5 py-3 font-medium text-slate-900">{{ $item->name }}<p class="text-xs font-normal text-slate-500">{{ $item->category }}</p></td>
                    <td class="px-5 py-3"><a href="{{ route('labs.show', $item->lab) }}" class="text-brand-700 hover:underline">{{ $item->lab->name }}</a></td>
                    <td class="px-5 py-3 text-slate-600">{{ $item->conditionLabel() }}</td>
                    <td class="px-5 py-3"><span class="font-semibold">{{ $item->availableStock() }}</span><span class="text-slate-400"> / {{ $item->stock }}</span></td>
                    <td class="whitespace-nowrap px-5 py-3 text-right">
                        <a href="{{ route('items.edit', $item) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                        <form method="POST" action="{{ route('items.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Tidak ada barang.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
