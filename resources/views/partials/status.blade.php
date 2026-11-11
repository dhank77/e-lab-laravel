@php
    $styles = [
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'approved' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'rejected' => 'bg-red-50 text-red-700 ring-red-200',
        'returned' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    ];
@endphp
<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $styles[$loan->status] ?? '' }}">{{ $loan->statusLabel() }}</span>
@if ($loan->isOverdue())
    <span class="ml-1 inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">Terlambat</span>
@endif
