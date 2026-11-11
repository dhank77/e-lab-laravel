{{-- Usage: @include('partials.field', ['name'=>'', 'label'=>'', 'type'=>'text', 'value'=>'', 'required'=>true]) --}}
<div>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $label }} @if($required ?? false)<span class="text-red-500">*</span>@endif</label>
    @if (($type ?? 'text') === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="3"
            class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm shadow-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 @error($name) ring-red-400 @enderror">{{ old($name, $value ?? '') }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type ?? 'text' }}" value="{{ ($type ?? 'text') === 'password' ? '' : old($name, $value ?? '') }}"
            @if($required ?? false) required @endif {!! $attrs ?? '' !!}
            class="w-full rounded-lg px-3 py-2 text-sm shadow-sm ring-1 ring-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 @error($name) ring-red-400 @enderror">
    @endif
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
