<div class="flex flex-wrap gap-2">
    @foreach (['' => 'Semua'] + \App\Models\Loan::STATUSES as $key => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $key ?: null, 'page' => null]) }}"
           class="rounded-full px-3 py-1.5 text-xs font-semibold {{ (string) request('status') === (string) $key ? 'bg-brand-600 text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">{{ $label }}</a>
    @endforeach
</div>
