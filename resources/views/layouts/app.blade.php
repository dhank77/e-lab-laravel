@extends('layouts.base')

@section('body')
@php
    $nav = [
        ['Dashboard', 'dashboard', 'dashboard'],
        ['Data Lab', 'labs.index', 'labs.*'],
        ['Peminjaman Saya', 'loans.index', 'loans.*'],
    ];
    if (auth()->user()->isAdmin()) {
        $nav = [
            ['Dashboard', 'dashboard', 'dashboard'],
            ['Data Lab', 'labs.index', 'labs.*'],
            ['Data Barang', 'items.index', 'items.*'],
            ['Kelola Peminjaman', 'admin.loans.index', 'admin.loans.*'],
        ];
    }
@endphp
<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">EL</span>
            <span class="text-lg font-bold text-slate-900">E-Lab</span>
        </a>
        <nav class="hidden items-center gap-1 md:flex">
            @foreach ($nav as [$label, $route, $pattern])
                <a href="{{ route($route) }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold leading-tight text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->nim ?? 'Mahasiswa') }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Logout</button>
            </form>
        </div>
    </div>
    <nav class="flex gap-1 overflow-x-auto border-t border-slate-100 px-4 py-2 md:hidden">
        @foreach ($nav as [$label, $route, $pattern])
            <a href="{{ route($route) }}"
               class="whitespace-nowrap rounded-lg px-3 py-1.5 text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-brand-50 text-brand-700' : 'text-slate-600' }}">{{ $label }}</a>
        @endforeach
    </nav>
</header>

<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
    @include('partials.flash')
    @yield('content')
</main>
@endsection
