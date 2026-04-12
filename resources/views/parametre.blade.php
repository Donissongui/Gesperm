@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                <i class="fas fa-cogs"></i>
                Paramètres du Système
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Gestion des configurations principales
            </p>
        </div>

    </div>

    <!-- ================= PARAMETRES GRID ================= -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Grade -->
        <a href="{{ route('parametre.grades.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-star text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Grades
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des grades
                </p>

            </div>
        </a>


        <!-- Services -->
        <a href="{{ route('parametre.services.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-building text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Services
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des services
                </p>

            </div>
        </a>


        <!-- Brigades -->
        <a href="{{ route('parametre.brigades.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-helmet-safety text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Brigades
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Organisation des brigades
                </p>

            </div>
        </a>

        <!-- Fonctions -->
        <a href="{{ route('parametre.fonctions.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
   hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
            rounded-xl flex items-center justify-center mb-4
            group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-briefcase text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Fonctions
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des fonctions
                </p>

            </div>
        </a>

        <!-- Catégories -->
        <a href="{{ route('parametre.categories.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-sitemap text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Catégories
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Classification des grades
                </p>

            </div>
        </a>

        <!-- Villes -->
        <a href="{{ route('parametre.villes.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
    hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
            rounded-xl flex items-center justify-center mb-4
            group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-city text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Villes
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des villes de destination des permissions
                </p>

            </div>
        </a>

        <!-- Motifs -->
        <a href="{{ route('parametre.motifs.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
    hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
            rounded-xl flex items-center justify-center mb-4
            group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-file-signature text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Motifs de permission
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des motifs des permissions
                </p>

            </div>
        </a>

        <!-- Utilisateurs -->
        <a href="{{ route('parametre.utilisateurs.index') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-users text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Utilisateurs
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des comptes
                </p>

            </div>
        </a>

    </div>

@endsection
