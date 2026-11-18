<dialog id="loan-dialog" class="loan-dialog" aria-labelledby="loan-dialog-title" data-reopen="{{ $errors->any() ? 'true' : 'false' }}">
    <header>
        <h2 id="loan-dialog-title">Ajukan Peminjaman</h2>
        <p>Petugas laboratorium akan memeriksa pengajuan Anda.</p>
    </header>
    @if ($loanOptions->isEmpty())
        <p class="notice">Belum ada barang yang dapat dipinjam.</p>
    @endif
    <form method="POST" action="{{ route('mahasiswa.requests.store') }}">
        @csrf
        <div class="field">
            <label for="equipment_id">Barang yang dipinjam</label>
            <select id="equipment_id" name="equipment_id" required>
                <option value="">Pilih barang</option>
                @foreach ($loanOptions as $option)
                    <option value="{{ $option->id }}" @selected((string) old('equipment_id') === (string) $option->id)>
                        {{ $option->name }} (tersedia {{ $option->available_quantity }})
                    </option>
                @endforeach
            </select>
            @error('equipment_id') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="field">
            <label for="quantity">Jumlah pinjam (unit)</label>
            <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required>
            @error('quantity') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="loan-dates">
            <div class="field">
                <label for="borrow_date">Tanggal pinjam</label>
                <input id="borrow_date" name="borrow_date" type="date" min="{{ today()->toDateString() }}" value="{{ old('borrow_date') }}" required>
                @error('borrow_date') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="return_date">Tanggal rencana kembali</label>
                <input id="return_date" name="return_date" type="date" value="{{ old('return_date') }}" required>
                @error('return_date') <p class="error">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="field">
            <label for="purpose">Keperluan (opsional)</label>
            <textarea id="purpose" name="purpose" maxlength="1000" rows="3">{{ old('purpose') }}</textarea>
            @error('purpose') <p class="error">{{ $message }}</p> @enderror
        </div>
        <p class="field-help">Masa pinjam maksimal 30 hari. Pengajuan menunggu tidak mengurangi stok.</p>
        <div class="student-actions">
            <button class="secondary" type="button" data-loan-close>Batal</button>
            <button class="primary" type="submit" @disabled($loanOptions->isEmpty())>Kirim Pengajuan</button>
        </div>
    </form>
</dialog>
