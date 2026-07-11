@extends('layouts.public')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-[80vh] px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-center mb-2 text-icc-dark">Login</h1>
            <p class="text-sm text-center text-icc-gray mb-6">Masuk ke panel ICC</p>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-50 rounded-lg p-3 border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-icc-dark mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-icc-dark mb-1">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition"
                            style="padding-right:38px;">
                        <button type="button" id="toggle-password" tabindex="-1"
                            style="position:absolute;top:0;bottom:0;right:0;width:38px;display:flex;align-items:center;justify-content:center;border:none;background:none;cursor:pointer;color:#9ca3af;">
                            <svg id="eye-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-off-icon" width="20" height="20" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-[#FF1A1A] text-white rounded-lg px-4 py-2.5 font-semibold hover:bg-[#CC1515] transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('toggle-password');
        var input = document.getElementById('password');
        var eye = document.getElementById('eye-icon');
        var eyeOff = document.getElementById('eye-off-icon');

        btn.addEventListener('click', function() {
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            eye.classList.toggle('hidden');
            eyeOff.classList.toggle('hidden');
        });
    });
</script>
@endpush
@endsection
