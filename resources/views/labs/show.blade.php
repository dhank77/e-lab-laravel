@extends('layouts.app')
@section('title', $lab->name)

@section('content')
<a href="{{ route('labs.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">← Semua lab</a>

<div class="mt-4 rounded-xl border border-slate-200 bg-white p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <span class="rounded-md bg-brand-50 px-2 py-1 text-xs font-semibold text-brand-700">{{ $lab->code }}</span>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">{{ $lab->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $lab->location }} @if($lab->head) · Kepala Lab: {{ $lab->head }} @endif</p>
            @if ($lab->description)<p class="mt-3 max-w-2xl text-sm text-slate-600">{{ $lab->description }}</p>@endif
        </div>
        @if (auth()->user()->isAdmin())
            <div class="flex gap-2">
                <a href="{{ route('items.create', ['lab' => $lab->id]) }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">+ Tambah Barang</a>
                <a href="{{ route('labs.edit', $lab) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                <form method="POST" action="{{ route('labs.destroy', $lab) }}" onsubmit="return confirm('Hapus lab ini beserta semua barangnya?')">
                    @csrf @method('DELETE')
                    <button class="rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                </form>
            </div>
        @endif
    </div>
</div>

<h2 class="mb-3 mt-8 text-lg font-semibold text-slate-900">Daftar Barang</h2>
<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Kode</th>
                <th class="px-5 py-3">Nama Barang</th>
                <th class="px-5 py-3">Kategori</th>
                <th class="px-5 py-3">Kondisi</th>
                <th class="px-5 py-3">Tersedia</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($items as $item)
                @php $available = $item->availableStock(); @endphp
                <tr>
                    <td class="whitespace-nowrap px-5 py-3 font-mono text-xs text-slate-500">{{ $item->code }}</td>
                    <td class="px-5 py-3 font-medium text-slate-900">{{ $item->name }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $item->category ?? '-' }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $item->conditionLabel() }}</td>
                    <td class="px-5 py-3">
                        <span class="font-semibold {{ $available > 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ $available }}</span>
                        <span class="text-slate-400">/ {{ $item->stock }}</span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-3 text-right">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('items.edit', $item) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                            <form method="POST" action="{{ route('items.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                            </form>
                        @elseif ($available > 0)
                            <a href="{{ route('loans.create', $item) }}" class="rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-700">Pinjam</a>
                        @else
                            <span class="text-xs text-slate-400">Tidak tersedia</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada barang di lab ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
