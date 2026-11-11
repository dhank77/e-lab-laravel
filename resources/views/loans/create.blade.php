@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')

@section('content')
<a href="{{ route('labs.show', $item->lab) }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">← Kembali ke {{ $item->lab->name }}</a>

<div class="mt-4 grid gap-8 lg:grid-cols-3">
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $item->code }}</p>
        <h2 class="mt-1 text-xl font-bold text-slate-900">{{ $item->name }}</h2>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Lab</dt><dd class="text-right font-medium">{{ $item->lab->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Kategori</dt><dd class="font-medium">{{ $item->category ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Kondisi</dt><dd class="font-medium">{{ $item->conditionLabel() }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Tersedia</dt><dd class="font-bold text-brand-700">{{ $item->availableStock() }} / {{ $item->stock }}</dd></div>
        </dl>
        @if ($item->description)<p class="mt-4 text-sm text-slate-600">{{ $item->description }}</p>@endif
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 lg:col-span-2">
        <h1 class="text-xl font-bold text-slate-900">Form Peminjaman</h1>
        @if ($item->availableStock() < 1)
            <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">Barang ini sedang tidak tersedia untuk dipinjam.</p>
        @else
            <form method="POST" action="{{ route('loans.store', $item) }}" class="mt-6 space-y-5">
                @csrf
                @include('partials.field', ['name' => 'quantity', 'label' => 'Jumlah', 'type' => 'number', 'value' => 1, 'required' => true, 'attrs' => 'min="1" max="'.$item->availableStock().'"'])
                <div class="grid gap-5 sm:grid-cols-2">
                    @include('partials.field', ['name' => 'loan_date', 'label' => 'Tanggal Pinjam', 'type' => 'date', 'value' => now()->toDateString(), 'required' => true, 'attrs' => 'min="'.now()->toDateString().'"'])
                    @include('partials.field', ['name' => 'due_date', 'label' => 'Tanggal Kembali', 'type' => 'date', 'value' => now()->addDays(3)->toDateString(), 'required' => true, 'attrs' => 'min="'.now()->toDateString().'"'])
                </div>
                @include('partials.field', ['name' => 'purpose', 'label' => 'Keperluan', 'required' => true, 'attrs' => 'placeholder="Contoh: Praktikum Jaringan Komputer modul 3"'])
                <button class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Ajukan Peminjaman</button>
            </form>
        @endif
    </div>
</div>
@endsection
