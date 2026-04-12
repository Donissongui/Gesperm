@extends('layouts.welcome')

@section('title', 'Accueil')

@section('content')

    <!-- HERO PREMIUM -->
    <section class="max-w-6xl mx-auto text-center py-20 px-4">

        <h1 class="text-4xl md:text-6xl font-bold leading-tight text-gray-800 mb-8 tracking-tight">
            Plateforme de gestion et de suivi des<br>
            <span class="text-[#C1272D]">habilitations et titres de permission</span>
        </h1>

        <p class="text-base md:text-lg text-gray-500 max-w-3xl mx-auto mb-12 leading-relaxed">
            Plateforme intelligente dédiée à la gouvernance des accès,
            à la gestion centralisée des habilitations et au contrôle sécurisé des autorisations
            au sein du système d’information.
        </p>

        <div class="flex justify-center gap-5 flex-wrap">

            <a href="{{ route('dashboard') }}"
                class="px-7 md:px-9 py-3 bg-[#C1272D] text-white rounded-xl shadow-md
           hover:bg-red-700 hover:-translate-y-1 transition duration-300 text-sm md:text-base">
                🚀 Commencer
            </a>

            <a href="#"
                class="px-7 md:px-9 py-3 bg-white border border-gray-200 rounded-xl
           hover:border-[#006233] hover:text-[#006233]
           hover:-translate-y-1 transition duration-300 shadow-sm text-sm md:text-base">
                🔍 Découvrir
            </a>

        </div>

    </section>

    <!-- FEATURES SECTION -->
    <section class="max-w-7xl mx-auto px-4 pb-20">

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7 md:gap-10">

            <!-- CARD 1 -->
            <div
                class="group bg-white backdrop-blur-lg p-7 md:p-10 rounded-2xl
border border-gray-100 shadow-sm hover:shadow-xl
hover:-translate-y-2 transition duration-300">

                <div
                    class="w-14 h-14 flex items-center justify-center
bg-[#C1272D]/10 text-[#C1272D] rounded-xl mb-6 text-2xl
group-hover:scale-110 transition">
                    🔐
                </div>

                <h3 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">
                    Contrôle des Permissions
                </h3>

                <p class="text-sm md:text-base text-gray-500 leading-relaxed">
                    Gestion sécurisée des habilitations avec une structure d’accès rigoureuse et auditable.
                </p>

            </div>

            <!-- CARD 2 -->
            <div
                class="group bg-white p-7 md:p-10 rounded-2xl border border-gray-100
shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-300">

                <div
                    class="w-14 h-14 flex items-center justify-center
bg-[#006233]/10 text-[#006233] rounded-xl mb-6 text-2xl
group-hover:scale-110 transition">
                    👤
                </div>

                <h3 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">
                    Gestion des Utilisateurs
                </h3>

                <p class="text-sm md:text-base text-gray-500 leading-relaxed">
                    Administration des profils et attribution intelligente des droits d’accès au système.
                </p>

            </div>

            <!-- CARD 3 -->
            <div
                class="group bg-white p-7 md:p-10 rounded-2xl border border-gray-100
shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-300">

                <div
                    class="w-14 h-14 flex items-center justify-center
bg-[#FFF44F]/40 text-gray-800 rounded-xl mb-6 text-2xl
group-hover:scale-110 transition">
                    📊
                </div>

                <h3 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">
                    Audit & Sécurité
                </h3>

                <p class="text-sm md:text-base text-gray-500 leading-relaxed">
                    Traçabilité complète des opérations pour garantir l’intégrité et la conformité.
                </p>

            </div>

        </div>

    </section>

@endsection
