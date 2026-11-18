@extends('petugas.layout')

@section('title', 'Kategori Alat')

@section('petugas-content')
    <section class="panel dashboard inventory-panel">
        <h1>Kategori Alat</h1>
        <p>Kategori berdasarkan data alat yang tersimpan di inventaris.</p>
        <ul class="inventory-list">
            @forelse ($categories as $category)
                <li>
                    <div>
                        <strong>{{ $category->category }}</strong>
                        <p>{{ $category->equipment_count }} jenis alat</p>
                    </div>
                </li>
            @empty
                <li><p>Belum ada kategori. Kategori akan muncul setelah alat pertama disimpan.</p></li>
            @endforelse
        </ul>
    </section>
@endsection
