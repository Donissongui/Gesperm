@extends('layouts.admin')

@section('title', 'Permissions')

@section('content')

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                <i class="fas fa-user-clock"></i>
                Gestion des permissions
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Gestion des permissions du personnel militaire et des stagiaires
            </p>
        </div>

    </div>

    <!-- ================= PERMISSIONS GRID ================= -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Permissions Militaires -->
        <a href="{{ route('permissions.liste', ['type' => 'militaire']) }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-person-military-rifle text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Permission Militaire
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Création d’une permission pour le personnel militaire
                </p>

            </div>
        </a>


        <!-- Permissions Stagiaires -->
        @if (Auth::user()->type == 'admin' || Auth::user()->type == 'SGS')
            <a href="{{ route('permissions.liste', ['type' => 'stagiaire']) }}"
                class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

                <div class="flex flex-col items-center text-center">

                    <div
                        class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                        <i class="fas fa-user-graduate text-xl"></i>

                    </div>

                    <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                        Permission Stagiaire
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Création d’une permission pour les stagiaires
                    </p>

                </div>
            </a>
        @endif
        <!-- Permissions -->
        <a href="{{ route('permissions.encours') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
   hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
           rounded-xl flex items-center justify-center mb-4
           group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-hourglass-half text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Suivi des permissions
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Consultation des permissions en cours et expirées
                </p>

            </div>
        </a>

        <!-- Historique -->
        <a href="{{ route('permissions.historique') }}"
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-clock-rotate-left text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Historique des permissions
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Consultation de l’historique des permissions
                </p>

            </div>
        </a>

        <!-- Statistiques -->
        <a href=""
            class="bg-white rounded-2xl shadow-sm border p-6
        hover:shadow-lg hover:-translate-y-1 transition duration-300 group">

            <div class="flex flex-col items-center text-center">

                <div
                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                rounded-xl flex items-center justify-center mb-4
                group-hover:bg-[#4B0082] group-hover:text-white transition">

                    <i class="fas fa-chart-bar text-xl"></i>

                </div>

                <h3 class="font-semibold text-gray-700 group-hover:text-[#4B0082] text-lg">
                    Statistiques
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Analyse et statistiques des permissions
                </p>

            </div>
        </a>

    </div>

@endsection
