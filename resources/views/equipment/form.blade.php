<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @isset($equipment)
        @method('PUT')
    @endisset
    <div class="field">
        <label for="name">Nama alat</label>
        <input id="name" name="name" value="{{ old('name', $equipment->name ?? null) }}" maxlength="120" placeholder="Contoh: Multimeter digital" required>
        @error('name') <p class="error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="field">
        <label for="available_quantity">Jumlah tersedia</label>
        <input id="available_quantity" name="available_quantity" type="number" value="{{ old('available_quantity', $equipment->available_quantity ?? null) }}" min="0" max="2147483647" step="1" placeholder="Contoh: 5" required>
        @error('available_quantity') <p class="error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="field">
        <label for="condition">Kondisi</label>
        <select id="condition" name="condition" required>
            <option value="">Pilih kondisi</option>
            <option value="Baik" @selected(old('condition', $equipment->condition ?? '') === 'Baik')>Baik</option>
            <option value="Buruk" @selected(old('condition', $equipment->condition ?? '') === 'Buruk')>Buruk</option>
        </select>
        @error('condition') <p class="error" role="alert">{{ $message }}</p> @enderror
    </div>
    <div class="field">
        <label for="photo">Foto alat (opsional)</label>
        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="photo-help">
        <p class="field-help" id="photo-help">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
        @error('photo') <p class="error" role="alert">{{ $message }}</p> @enderror
    </div>
    <button class="primary" type="submit">Simpan Barang</button>
</form>
