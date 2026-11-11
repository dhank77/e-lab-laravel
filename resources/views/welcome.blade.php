@extends('layouts.base')
@section('title', 'Selamat Datang')

@section('body')
<div class="relative isolate overflow-hidden">
    <header class="mx-auto flex max-w-6xl items-center justify-between px-4 py-6 sm:px-6">
        <div class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">EL</span>
            <span class="text-lg font-bold">E-Lab</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('login') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Login</a>
            <a href="{{ route('register') }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Register</a>
        </div>
    </header>
    <section class="mx-auto max-w-6xl px-4 pb-24 pt-16 sm:px-6 lg:pt-24">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Sistem Peminjaman Laboratorium</p>
            <h1 class="mt-3 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">Semua alat lab, satu tempat untuk meminjamnya.</h1>
            <p class="mt-5 text-lg text-slate-600">Lihat data laboratorium, cek stok barang yang tersedia, ajukan peminjaman, dan pantau status persetujuannya secara online.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-lg bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-brand-700">Mulai Sekarang</a>
                <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Saya sudah punya akun</a>
            </div>
        </div>
        <div class="mt-20 grid gap-6 sm:grid-cols-3">
            @foreach ([['Data Lab Lengkap', 'Informasi lokasi, kepala lab, dan inventaris setiap laboratorium.'], ['Stok Real-time', 'Jumlah barang tersedia dihitung otomatis dari peminjaman aktif.'], ['Persetujuan Admin', 'Pengajuan diverifikasi admin, lalu dicatat sampai barang kembali.']] as [$t, $d])
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold text-slate-900">{{ $t }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </section>
    <div class="pointer-events-none absolute -right-40 top-0 -z-10 h-[32rem] w-[32rem] rounded-full bg-brand-100 blur-3xl"></div>
</div>
@endsection
