@if (in_array($loan->status, ['pending', 'approved']))
    <form method="POST" action="{{ route('admin.loans.update', $loan) }}" class="flex flex-col items-end gap-2">
        @csrf @method('PATCH')
        <input name="admin_note" placeholder="Catatan (opsional)" class="w-44 rounded-lg px-2 py-1 text-xs ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500">
        <div class="flex gap-1.5">
            @if ($loan->status === 'pending')
                <button name="action" value="approve" class="rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-700">Setujui</button>
                <button name="action" value="reject" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Tolak</button>
            @else
                <button name="action" value="return" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Tandai Dikembalikan</button>
            @endif
        </div>
    </form>
@else
    <span class="text-xs text-slate-400">Selesai</span>
@endif
