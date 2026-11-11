@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
<p class="mt-1 text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Login</a></p>

<form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
    @csrf
    @include('partials.field', ['name' => 'name', 'label' => 'Nama Lengkap', 'required' => true, 'attrs' => 'autofocus'])
    <div class="grid gap-5 sm:grid-cols-2">
        @include('partials.field', ['name' => 'nim', 'label' => 'NIM', 'required' => true])
        @include('partials.field', ['name' => 'phone', 'label' => 'No. HP'])
    </div>
    @include('partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true])
    <div class="grid gap-5 sm:grid-cols-2">
        @include('partials.field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true, 'attrs' => 'autocomplete="new-password"'])
        @include('partials.field', ['name' => 'password_confirmation', 'label' => 'Ulangi Password', 'type' => 'password', 'required' => true, 'attrs' => 'autocomplete="new-password"'])
    </div>
    <button class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700">Daftar</button>
</form>
@endsection
