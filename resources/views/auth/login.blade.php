@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<h1 class="text-2xl font-bold text-slate-900">Masuk ke E-Lab</h1>
<p class="mt-1 text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Daftar di sini</a></p>

<form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
    @csrf
    @include('partials.field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'attrs' => 'autofocus autocomplete="email"'])
    @include('partials.field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true, 'attrs' => 'autocomplete="current-password"'])
    <label class="flex items-center gap-2 text-sm text-slate-600">
        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600"> Ingat saya
    </label>
    <button class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700">Login</button>
</form>
@endsection
