@extends('layout')

@section('header-start')
    <button class="secondary admin-menu-toggle" id="admin-menu-toggle" type="button"
        aria-label="Menu petugas" aria-controls="admin-menu" aria-expanded="true" hidden>
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" aria-hidden="true">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
@endsection

@section('header')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="secondary" type="submit">Keluar</button>
    </form>
@endsection

@section('content')
    <div class="admin-shell" id="admin-shell">
        <nav class="panel dashboard admin-menu" id="admin-menu" aria-label="Menu petugas">
            <span class="tag">PETUGAS</span>
            <ul>
                @foreach ([
                    'petugas.dashboard' => 'Dashboard',
                    'petugas.equipment.index' => 'Data Lab',
                    'petugas.requests' => 'Pengajuan',
                    'petugas.history' => 'Riwayat Transaksi',
                ] as $route => $label)
                    <li>
                        <a href="{{ route($route) }}" @if (request()->routeIs($route)) aria-current="page" @endif>
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="admin-content">
            @yield('petugas-content')
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('admin-menu.js') }}"></script>
@endsection