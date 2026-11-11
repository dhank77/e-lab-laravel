@extends('layouts.base')

@section('body')
<div class="grid min-h-screen lg:grid-cols-2">
    <div class="relative hidden overflow-hidden bg-brand-900 p-12 text-white lg:flex lg:flex-col lg:justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="grid h-10 w-10 place-items-center rounded-lg bg-white/15 font-bold">EL</span>
            <span class="text-xl font-bold">E-Lab</span>
        </a>
        <div class="max-w-md">
            <h2 class="text-3xl font-bold leading-tight">Pinjam alat laboratorium tanpa antre dan tanpa kertas.</h2>
            <p class="mt-4 text-brand-100">Cek ketersediaan barang di setiap lab, ajukan peminjaman, dan pantau statusnya dari satu tempat.</p>
        </div>
        <p class="text-sm text-brand-100/70">&copy; {{ date('Y') }} Sistem Informasi Laboratorium</p>
        <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brand-500/30 blur-3xl"></div>
    </div>
    <div class="flex items-center justify-center px-4 py-12 sm:px-8">
        <div class="w-full max-w-md">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
</div>
@endsection
