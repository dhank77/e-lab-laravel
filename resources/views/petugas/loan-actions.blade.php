<div class="loan-controls">
    @if ($loan->status === 'menunggu' || $loan->status === 'disetujui')
        <form method="POST" action="{{ route('petugas.requests.process', $loan) }}">
            @csrf
            @method('PATCH')
            @if ($loan->status === 'menunggu')
                <button class="secondary" name="action" value="approve">Setujui</button>
            @else
                <button class="secondary" name="action" value="handover">Catat Penyerahan</button>
            @endif
        </form>
        <details>
            <summary>Tolak</summary>
            <form method="POST" action="{{ route('petugas.requests.process', $loan) }}">
                @csrf
                @method('PATCH')
                <input name="reason" aria-label="Alasan penolakan" placeholder="Alasan penolakan" maxlength="500" required>
                <button class="secondary" name="action" value="reject">Tolak Pengajuan</button>
            </form>
        </details>
    @elseif ($loan->status === 'dipinjam')
        <form method="POST" action="{{ route('petugas.requests.process', $loan) }}">
            @csrf
            @method('PATCH')
            <select name="condition" aria-label="Kondisi saat kembali" required>
                <option value="">Kondisi saat kembali</option>
                <option value="Baik">Baik</option>
                <option value="Buruk">Buruk</option>
            </select>
            <button class="secondary" name="action" value="return">Catat Pengembalian</button>
        </form>
    @endif
</div>
