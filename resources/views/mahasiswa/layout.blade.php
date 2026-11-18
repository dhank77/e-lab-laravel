@extends('layout')

@section('header-start')
    <button class="secondary admin-menu-toggle" id="admin-menu-toggle" type="button"
        aria-label="Menu mahasiswa" aria-controls="admin-menu" aria-expanded="true" hidden>☰</button>
@endsection

@section('header')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="secondary" type="submit">Keluar</button>
    </form>
@endsection

@section('content')
    <div class="admin-shell" id="admin-shell">
        <nav class="panel dashboard admin-menu" id="admin-menu" aria-label="Menu mahasiswa">
            <span class="tag">MAHASISWA</span>
            <ul>
                @foreach (['mahasiswa.dashboard' => 'Katalog Alat', 'mahasiswa.requests' => 'Pengajuan Saya'] as $route => $label)
                    <li><a href="{{ route($route) }}" @if (request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
            </ul>
            <p class="student-account">{{ auth()->user()->name }}</p>
        </nav>
        <div class="admin-content">
            @yield('student-content')
            @include('mahasiswa.loan-form')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin-menu.js') }}"></script>
    <script src="{{ asset('student-loan.js') }}"></script>
@endsection
