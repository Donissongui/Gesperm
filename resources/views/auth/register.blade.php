@extends('layouts.welcome')

@section('title', 'Créer un compte')

@section('content')

    <div class="flex justify-center items-center min-h-[70vh] px-4">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border p-6 md:p-8 transition-all">

            <h2 class="text-xl md:text-2xl font-bold text-center mb-2">
                Création de compte
            </h2>

            <p class="text-sm text-gray-500 text-center mb-6">
                Inscrivez-vous pour accéder au système
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Nom</label>

                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full border rounded-xl px-4 py-2.5 text-sm md:text-base
                              focus:ring-2 focus:ring-[#C1272D]
                              outline-none transition">

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>

                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border rounded-xl px-4 py-2.5 text-sm md:text-base
                              focus:ring-2 focus:ring-[#C1272D]
                              outline-none transition">

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Mot de passe</label>

                    <input type="password" name="password" required
                        class="w-full border rounded-xl px-4 py-2.5 text-sm md:text-base
                              focus:ring-2 focus:ring-[#C1272D]
                              outline-none transition">

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm mb-1">Confirmer mot de passe</label>

                    <input type="password" name="password_confirmation" required
                        class="w-full border rounded-xl px-4 py-2.5 text-sm md:text-base
                              focus:ring-2 focus:ring-[#C1272D]
                              outline-none transition">

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">

                    <button type="submit"
                        class="w-full bg-[#C1272D] text-white py-2.5 rounded-xl shadow hover:shadow-lg transition font-medium">
                        S'inscrire
                    </button>

                    <a href="{{ route('login') }}"
                        class="text-center text-sm text-gray-500 hover:text-[#C1272D] transition">
                        Déjà un compte ? Connexion
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection
