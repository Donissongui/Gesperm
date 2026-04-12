@extends('layouts.connexion')

@section('title', 'Mot de passe oublié')

@section('content')

    <div class="flex justify-center items-center min-h-screen px-4 py-6">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border p-5 sm:p-6 md:p-8 flex flex-col">

            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-center mb-2 text-[#4B0082]">
                Mot de passe oublié
            </h2>

            <p class="text-xs sm:text-sm text-gray-500 text-center mb-5">
                {{ __('Entrez votre email pour recevoir le lien de réinitialisation') }}
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">

                    <label class="block text-xs sm:text-sm mb-1">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border rounded-xl px-3 sm:px-4 py-2.5 text-sm sm:text-base
                              focus:ring-2 focus:ring-[#4B0082]
                              focus:border-[#4B0082]
                              outline-none transition">

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-[#4B0082] text-white py-2.5 rounded-xl shadow
                       hover:bg-[#3a0068] hover:shadow-lg transition
                       font-medium text-sm sm:text-base">

                    Envoyer le lien de réinitialisation

                </button>

                <!-- Back login -->
                <div class="text-center mt-5 text-xs sm:text-sm">

                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-[#FFD700] underline transition">

                        Retour à la connexion

                    </a>

                </div>

            </form>

            <!-- COPYRIGHT -->
            <div class="mt-6 pt-4 border-t text-center text-[10px] sm:text-xs text-gray-400">

                © {{ date('Y') }} Gestion Permission — Système sécurisé

            </div>

        </div>

    </div>

@endsection
