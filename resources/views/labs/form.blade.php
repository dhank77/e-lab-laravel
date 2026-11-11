@extends('layouts.app')
@section('title', $lab->exists ? 'Edit Lab' : 'Tambah Lab')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ $lab->exists ? route('labs.show', $lab) : route('labs.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">← Kembali</a>
    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-6">
        <h1 class="text-xl font-bold text-slate-900">{{ $lab->exists ? 'Edit Lab' : 'Tambah Lab' }}</h1>
        <form method="POST" action="{{ $lab->exists ? route('labs.update', $lab) : route('labs.store') }}" class="mt-6 space-y-5">
            @csrf
            @if ($lab->exists) @method('PUT') @endif
            <div class="grid gap-5 sm:grid-cols-3">
                @include('partials.field', ['name' => 'code', 'label' => 'Kode Lab', 'value' => $lab->code, 'required' => true])
                <div class="sm:col-span-2">@include('partials.field', ['name' => 'name', 'label' => 'Nama Lab', 'value' => $lab->name, 'required' => true])</div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                @include('partials.field', ['name' => 'location', 'label' => 'Lokasi', 'value' => $lab->location])
                @include('partials.field', ['name' => 'head', 'label' => 'Kepala Lab', 'value' => $lab->head])
            </div>
            @include('partials.field', ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'value' => $lab->description])
            <button class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Simpan</button>
        </form>
    </div>
</div>
@endsection
