@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-doc-bg">

    <!-- LEFT SIDE : LOGIN FORM -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white">
        <div class="w-full max-w-md px-10">

            <!-- Brand -->
            <div class="mb-12">
                <span class="text-lg font-medium text-doc-text">
                    Docmanager
                </span>
            </div>

            <!-- Heading -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-doc-text mb-2">
                    Welcome Back
                </h1>
                <p class="text-sm text-gray-500">
                    Enter your email and password to access your account
                </p>
            </div>

            <!-- Form -->
            <form class="space-y-6">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-doc-text mb-1">
                        Email
                    </label>
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="w-full rounded-md bg-gray-100
                               px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-doc-accent"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-doc-text mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        placeholder="Enter your password"
                        class="w-full rounded-md bg-gray-100
                               px-4 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-doc-accent"
                    >
                </div>

                <!-- Forgot -->
                <div class="text-right">
                    <a href="#" class="text-sm text-doc-primary hover:underline">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg
                           bg-doc-primary text-white font-medium
                           hover:bg-doc-accent transition-colors"
                >
                    Sign In
                </button>

            </form>
        </div>
    </div>

    <!-- RIGHT SIDE : ILLUSTRATION -->
    <div class="hidden lg:flex w-1/2 bg-doc-primary relative items-center justify-center">

        <!-- Quote -->
        <div class="absolute top-1/3 px-12 text-center">
            <h2 class="text-3xl font-bold text-white leading-snug">
                “Make your <span class="text-doc-secondary">documents</span> easy<br>
                to <span class="text-doc-secondary">manage</span>”
            </h2>
        </div>

        <!-- Illustration -->
        <img
            src="{{ asset('assets/img/illustration1.svg') }}"
            alt="Login Illustration"
            class="absolute bottom-0 max-h-[70%]"
        >
    </div>

</div>
@endsection
