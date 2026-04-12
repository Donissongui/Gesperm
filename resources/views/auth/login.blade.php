@extends('layouts.connexion')

@section('title', 'Connexion')

@section('content')

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border p-5 sm:p-6 md:p-8 flex flex-col">

        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/CIT.png') }}" class="w-16 h-16 sm:w-20 sm:h-20 object-contain" alt="logo">
        </div>

        <h2 class="text-xl md:text-2xl font-bold text-center mb-1 text-[#4B0082]">
            Connexion
        </h2>

        <p class="text-xs sm:text-sm text-gray-500 text-center mb-6">
            Accédez à votre espace sécurisé
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading=true">

            @csrf

            <!-- Login -->
            <div class="mb-4">
                <label class="block text-xs sm:text-sm mb-1">
                    Login ou Email
                </label>

                <input type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                    placeholder="Entrez votre login ou email"
                    class="w-full border rounded-xl px-4 py-2.5
                          focus:ring-2 focus:ring-[#4B0082]
                          focus:border-[#4B0082]
                          outline-none transition">

                <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4" x-data="{ show: false }">

                <label class="block text-xs sm:text-sm mb-1">
                    Mot de passe
                </label>

                <div class="relative">

                    <input :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                        class="w-full border rounded-xl px-4 py-2.5
                              focus:ring-2 focus:ring-[#4B0082]
                              focus:border-[#4B0082]
                              outline-none transition pr-10">

                    <button type="button" @click="show=!show"
                        class="absolute right-3 top-1/2 -translate-y-1/2
                               text-gray-500 hover:text-[#FFD700] transition">

                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>

                    </button>

                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember -->
            <div class="flex items-center gap-2 text-sm mb-5">

                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#4B0082] focus:ring-[#4B0082]">

                <span class="text-gray-600">
                    Se souvenir de moi
                </span>
            </div>

            <!-- Submit Button with Loader -->
            <button type="submit" :disabled="loading"
                class="w-full bg-[#4B0082] text-white py-2.5 rounded-xl shadow
                       hover:bg-[#3a0068] hover:shadow-lg transition
                       flex items-center justify-center gap-2">

                <!-- Normal text -->
                <span x-show="!loading">
                    Connexion
                </span>

                <!-- Loading state -->
                <span x-show="loading" class="flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    Connexion...
                </span>

            </button>

            <!-- Forgot -->
            {{-- <div class="text-center mt-5 text-xs sm:text-sm">

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-gray-500 hover:text-[#FFD700] underline transition">
                        Mot de passe oublié ?
                    </a>
                @endif

            </div> --}}

        </form>

        <!-- Copyright -->
        <div class="mt-6 pt-4 border-t text-center text-[10px] sm:text-xs text-gray-400">
            © {{ date('Y') }} Gestion Permission — Système sécurisé
        </div>

    </div>

@endsection
