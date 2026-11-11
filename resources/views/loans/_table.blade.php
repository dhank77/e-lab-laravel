<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                @if ($admin)<th class="px-5 py-3">Peminjam</th>@endif
                <th class="px-5 py-3">Barang</th>
                <th class="px-5 py-3">Jumlah</th>
                <th class="px-5 py-3">Tanggal</th>
                <th class="px-5 py-3">Status</th>
                @if (! empty($actionView))<th class="px-5 py-3 text-right">Aksi</th>@endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($loans as $loan)
                <tr class="align-top">
                    @if ($admin)
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-900">{{ $loan->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $loan->user->nim ?? $loan->user->email }}</p>
                        </td>
                    @endif
                    <td class="px-5 py-3">
                        <p class="font-medium text-slate-900">{{ $loan->item->name }}</p>
                        <p class="text-xs text-slate-500">{{ $loan->item->lab->name }}</p>
                        <p class="mt-1 text-xs text-slate-500">Keperluan: {{ $loan->purpose }}</p>
                        @if ($loan->admin_note)<p class="mt-1 text-xs italic text-slate-500">Catatan admin: {{ $loan->admin_note }}</p>@endif
                    </td>
                    <td class="px-5 py-3">{{ $loan->quantity }}</td>
                    <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                        {{ $loan->loan_date->translatedFormat('d M Y') }} – {{ $loan->due_date->translatedFormat('d M Y') }}
                        @if ($loan->returned_at)<p class="text-xs text-emerald-600">Kembali {{ $loan->returned_at->translatedFormat('d M Y H:i') }}</p>@endif
                    </td>
                    <td class="whitespace-nowrap px-5 py-3">@include('partials.status')</td>
                    @if (! empty($actionView))<td class="px-5 py-3 text-right">@include($actionView)</td>@endif
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
