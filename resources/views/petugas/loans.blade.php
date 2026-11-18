@extends('petugas.layout')

@section('title', $history ? 'Riwayat Transaksi' : 'Pengajuan')

@section('petugas-content')
    <header class="student-heading">
        <div>
            <h1>{{ $history ? 'Riwayat Transaksi' : 'Pengajuan Peminjaman' }}</h1>
            <p>{{ $history ? 'Lihat hasil pengajuan serta tanggal penyerahan dan pengembalian.' : 'Periksa pengajuan mahasiswa dan catat penyerahan atau pengembalian alat.' }}</p>
        </div>
    </header>

    @if (session('status'))
        <p class="notice success" role="status">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p class="notice error" role="alert">{{ $errors->first() }}</p>
    @endif

    <section class="lab-table-panel admin-loans" aria-label="{{ $history ? 'Riwayat transaksi' : 'Daftar pengajuan' }}">
        <div class="lab-table-scroll" tabindex="0" role="region" aria-label="Tabel peminjaman">
            <table class="lab-table loan-table">
                <thead>
                    <tr>
                        <th scope="col">Mahasiswa</th>
                        <th scope="col">Alat</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Jadwal</th>
                        <th scope="col">Status</th>
                        <th scope="col">{{ $history ? 'Catatan' : 'Aksi' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loan->borrower->name }}</td>
                            <td>
                                <strong>{{ $loan->equipment_name }}</strong>
                                @if ($loan->purpose) <small>{{ $loan->purpose }}</small> @endif
                            </td>
                            <td>{{ $loan->quantity }} unit</td>
                            <td>{{ $loan->borrow_date }} <small>s.d. {{ $loan->return_date }}</small></td>
                            <td><span class="lab-condition">{{ \App\Models\LoanRequest::STATUSES[$loan->status] ?? $loan->status }}</span></td>
                            <td>
                                @if ($history)
                                    @if ($loan->handed_over_at) <small>Diserahkan {{ $loan->handed_over_at->format('d/m/Y') }}</small> @endif
                                    @if ($loan->returned_at) <small>Dikembalikan {{ $loan->returned_at->format('d/m/Y') }} · {{ $loan->return_condition }}</small> @endif
                                    @if ($loan->rejection_reason) <small>Alasan: {{ $loan->rejection_reason }}</small> @endif
                                    @if (!$loan->handed_over_at && !$loan->rejection_reason) &mdash; @endif
                                @else
                                    @include('petugas.loan-actions')
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">{{ $history ? 'Belum ada riwayat transaksi.' : 'Belum ada pengajuan yang perlu diproses.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('mahasiswa.pagination', ['pages' => $loans])
    </section>
@endsection
