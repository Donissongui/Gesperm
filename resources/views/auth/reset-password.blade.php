@extends('layouts.welcome')

@section('title', 'Réinitialiser mot de passe')

@section('content')

    <div class="flex justify-center items-center min-h-[70vh]">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border p-8">

            <h2 class="text-2xl font-bold text-center mb-6">
                Réinitialiser mot de passe
            </h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>

                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#C1272D] outline-none">

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Nouveau mot de passe</label>

                    <input type="password" name="password" required
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#C1272D] outline-none">

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm mb-1">Confirmer mot de passe</label>

                    <input type="password" name="password_confirmation" required
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#C1272D] outline-none">

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-[#C1272D] text-white py-2 rounded-xl shadow hover:shadow-lg transition">
                    Réinitialiser mot de passe
                </button>

            </form>

        </div>

    </div>

@endsection
