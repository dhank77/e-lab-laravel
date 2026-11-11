@if ($loan->status === 'pending')
    <form method="POST" action="{{ route('loans.cancel', $loan) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
        @csrf @method('DELETE')
        <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Batalkan</button>
    </form>
@else
    <span class="text-xs text-slate-400">—</span>
@endif
