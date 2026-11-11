@extends('layouts.app')
@section('title', $item->exists ? 'Edit Barang' : 'Tambah Barang')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ $item->lab_id ? route('labs.show', $item->lab_id) : route('items.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">← Kembali</a>
    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-6">
        <h1 class="text-xl font-bold text-slate-900">{{ $item->exists ? 'Edit Barang' : 'Tambah Barang' }}</h1>
        <form method="POST" action="{{ $item->exists ? route('items.update', $item) : route('items.store') }}" class="mt-6 space-y-5">
            @csrf
            @if ($item->exists) @method('PUT') @endif
            <div>
                <label for="lab_id" class="mb-1.5 block text-sm font-medium text-slate-700">Laboratorium <span class="text-red-500">*</span></label>
                <select id="lab_id" name="lab_id" required class="w-full rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">— Pilih lab —</option>
                    @foreach ($labs as $lab)<option value="{{ $lab->id }}" @selected(old('lab_id', $item->lab_id) == $lab->id)>{{ $lab->name }}</option>@endforeach
                </select>
                @error('lab_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                @include('partials.field', ['name' => 'code', 'label' => 'Kode Barang', 'value' => $item->code, 'required' => true])
                <div class="sm:col-span-2">@include('partials.field', ['name' => 'name', 'label' => 'Nama Barang', 'value' => $item->name, 'required' => true])</div>
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                @include('partials.field', ['name' => 'category', 'label' => 'Kategori', 'value' => $item->category])
                @include('partials.field', ['name' => 'stock', 'label' => 'Jumlah Stok', 'type' => 'number', 'value' => $item->stock, 'required' => true, 'attrs' => 'min="0"'])
                <div>
                    <label for="condition" class="mb-1.5 block text-sm font-medium text-slate-700">Kondisi <span class="text-red-500">*</span></label>
                    <select id="condition" name="condition" class="w-full rounded-lg px-3 py-2 text-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @foreach (\App\Models\Item::CONDITIONS as $key => $label)<option value="{{ $key }}" @selected(old('condition', $item->condition) === $key)>{{ $label }}</option>@endforeach
                    </select>
                </div>
            </div>
            @include('partials.field', ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'value' => $item->description])
            <button class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Simpan</button>
        </form>
    </div>
</div>
@endsection
