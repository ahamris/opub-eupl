@extends('layouts.app')

@section('title', 'Inloggen')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Inloggen</h1>
            <p class="mt-2 text-sm text-gray-600">Log in op uw account om toegang te krijgen tot uw dashboard</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
            <form method="POST" action="{{ route('user.login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition-colors @error('email') border-red-500 @enderror"
                        placeholder="uw@email.nl"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Wachtwoord</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition-colors @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[var(--color-primary)] border-gray-300 rounded focus:ring-[var(--color-primary)]">
                        <span class="ml-2 text-sm text-gray-600">Onthoud mij</span>
                    </label>
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-[var(--color-primary)] text-white font-semibold rounded-md hover:bg-[var(--color-primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] transition-colors"
                >
                    <i class="fas fa-sign-in-alt"></i>
                    Inloggen
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Of</span>
                </div>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600">
                Nog geen account?
                <a href="{{ route('user.register') }}" class="font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)]">
                    Registreren
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
