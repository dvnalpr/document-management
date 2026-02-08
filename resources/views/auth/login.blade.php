@extends('layouts.auth')

@section('content')
    <div class="min-h-screen w-full flex">

        <div class="w-full md:w-3/5 lg:w-2/3 flex flex-col justify-center px-8 md:px-16 lg:px-24 bg-white relative">

            <div class="absolute top-8 left-8 md:left-12">
                <img src="{{ asset('assets/img/Logo.png') }}" alt="Docmanager Logo" class="h-12 w-auto object-contain">
            </div>

            <div class="w-full max-w-md mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold mb-2 text-dark">Welcome Back</h2>
                    <p class="text-gray-500 mb-10 text-sm md:text-base">
                        Enter your email and password to access your account
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-dark">Email</label>
                        <input id="email" type="email" name="email" class="input-field" placeholder="Enter your email"
                            value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-dark">Password</label>
                        <input id="password" type="password" name="password" class="input-field"
                            placeholder="Enter your Password" required>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-medium text-dark hover:text-primary transition">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-primary text-white font-bold py-3.5 rounded-lg transition-all duration-300 shadow-lg hover:bg-opacity-90 hover:shadow-xl hover:-translate-y-1 transform active:scale-95">
                        Sign In
                    </button>
                </form>
            </div>
        </div>

        <div
            class="hidden md:flex md:w-2/5 lg:w-1/3 bg-primary flex-col justify-center items-center p-8 text-center relative overflow-hidden">

            <div class="relative z-10 w-full animate-fade-up">
                <h2 class="text-3xl lg:text-2xl font-bold text-white mb-8 leading-tight">
                    “Make your <span class="text-blue-200">documents</span> easy <br>
                    to <span class="text-blue-200">manage</span>”
                </h2>

                <img src="{{ asset('assets/img/illustration1.svg') }}" alt="Illustration"
                    class="w-100 mx-auto drop-shadow-2xl object-contain hover:scale-105 transition-transform duration-500"
                    onerror="this.style.display='none'">
            </div>

            <div
                class="absolute bottom-0 right-0 transform translate-x-1/3 translate-y-1/3 w-80 h-80 bg-white/5 rounded-full animate-float">
            </div>
            <div
                class="absolute top-0 left-0 transform -translate-x-1/3 -translate-y-1/3 w-48 h-48 bg-white/5 rounded-full animate-float-delayed">
            </div>
        </div>
    </div>
@endsection