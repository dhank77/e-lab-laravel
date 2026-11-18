@extends('mahasiswa.layout')

@section('title', 'Pengajuan Saya')

@section('student-content')
    <header class="student-heading">
        <div><h1>Pengajuan Saya</h1><p>Pantau status dan riwayat peminjaman Anda.</p></div>
        <button class="primary" type="button" data-loan-open>+ Ajukan Peminjaman</button>
    </header>

    @if (session('status'))
        <p class="notice success" role="status">{{ session('status') }}</p>
    @endif

    <section class="student-panel student-guide">
        <h2>Alur peminjaman dan pengembalian</h2>
        <ol>
            <li><strong>Ajukan</strong><p>Pilih alat, jumlah, dan tanggal peminjaman.</p></li>
            <li><strong>Tunggu persetujuan</strong><p>Petugas memeriksa pengajuan. Anda bisa membatalkannya selama masih menunggu.</p></li>
            <li><strong>Ambil alat</strong><p>Datang ke laboratorium dan tunjukkan kartu mahasiswa.</p></li>
            <li><strong>Kembalikan</strong><p>Serahkan alat ke petugas paling lambat pada tanggal rencana kembali.</p></li>
        </ol>
    </section>

    <section class="student-panel">
        <nav class="loan-statuses" aria-label="Filter status pengajuan">
            @foreach (['' => 'Semua'] + $statuses as $key => $label)
                <a href="{{ route('mahasiswa.requests', $key === '' ? [] : ['status' => $key]) }}"
                    @if ($status === $key) aria-current="page" @endif>
                    {{ $label }} <span>{{ $key === '' ? $counts->sum() : $counts->get($key, 0) }}</span>
                </a>
            @endforeach
        </nav>

        <ul class="inventory-list">
            @forelse ($loans as $loan)
                <li class="student-loan-row">
                    <div>
                        <strong>{{ $loan->equipment_name }}</strong>
                        <p>{{ $loan->quantity }} unit · {{ $loan->borrow_date }} sampai {{ $loan->return_date }}</p>
                        @if ($loan->purpose) <p>{{ $loan->purpose }}</p> @endif
                        @if ($loan->rejection_reason) <p>Alasan penolakan: {{ $loan->rejection_reason }}</p> @endif
                    </div>
                    <span class="lab-condition">{{ $statuses[$loan->status] ?? $loan->status }}</span>
                    @if ($loan->status === 'menunggu')
                        <form method="POST" action="{{ route('mahasiswa.requests.cancel', $loan) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
                            @csrf
                            @method('PATCH')
                            <button class="secondary" type="submit">Batalkan</button>
                        </form>
                    @endif
                </li>
            @empty
                <li class="student-empty"><strong>Belum ada pengajuan</strong><a href="{{ route('mahasiswa.dashboard') }}">Buka Katalog</a></li>
            @endforelse
        </ul>
        @include('mahasiswa.pagination', ['pages' => $loans])
    </section>
@endsection
