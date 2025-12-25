@extends('layouts.app')

@section('title', 'Registreren')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Account aanmaken</h1>
            <p class="mt-2 text-sm text-gray-600">Maak een account aan om gebruik te maken van uw persoonlijk dashboard</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 shadow-sm">
            <form method="POST" action="{{ route('user.register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Voornaam</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition-colors @error('name') border-red-500 @enderror"
                            placeholder="Voornaam"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Achternaam</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            value="{{ old('last_name') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition-colors @error('last_name') border-red-500 @enderror"
                            placeholder="Achternaam"
                        >
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
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
                        placeholder="Minimaal 8 karakters"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Bevestig wachtwoord</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition-colors"
                        placeholder="Herhaal uw wachtwoord"
                    >
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full flex justify-center items-center gap-2 py-3 px-4 bg-[var(--color-primary)] text-white font-semibold rounded-md hover:bg-[var(--color-primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] transition-colors"
                >
                    <i class="fas fa-user-plus"></i>
                    Account aanmaken
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

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600">
                Heeft u al een account?
                <a href="{{ route('user.login') }}" class="font-semibold text-[var(--color-primary)] hover:text-[var(--color-primary-dark)]">
                    Inloggen
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
