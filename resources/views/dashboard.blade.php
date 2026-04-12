@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <div class="px-2 sm:px-4 lg:px-2">

        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                    <i class="fas fa-chart-line"></i> Tableau de bord
                </h1>
                <p class="text-gray-500 text-xs sm:text-sm">
                    Bienvenue dans votre espace d’administration sécurisé
                </p>
            </div>
            <div class="text-xs sm:text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-calendar-alt"></i>
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <!-- ================= CARDS STATISTIQUES ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">

            <!-- Habilitations -->
            <div
                class="bg-white rounded-2xl shadow-md border p-4 sm:p-6 hover:shadow-lg transform hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm">Habilitations</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#4B0082] mt-1">{{ $habilitation ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-yellow-200 to-yellow-100 text-[#4B0082]">
                        <i class="fas fa-id-badge text-lg sm:text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Habilitations enregistrées</p>
            </div>

            <!-- Permissions -->
            <div
                class="bg-white rounded-2xl shadow-md border p-4 sm:p-6 hover:shadow-lg transform hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm">Permissions actives</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#4B0082] mt-1">{{ $permission ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-200 to-indigo-100  text-[#4B0082]">
                        <i class="fas fa-hourglass-half text-lg sm:text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Permissions actuellement valides</p>
            </div>

            <!-- Militaires -->
            <div
                class="bg-white rounded-2xl shadow-md border p-4 sm:p-6 hover:shadow-lg transform hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm">Militaires</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#4B0082] mt-1">{{ $militaire ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-200 to-blue-100 text-[#4B0082]">
                        <i class="fas fa-user-shield text-lg sm:text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Personnel enregistré</p>
            </div>

            <!-- Stagiaires -->
            <div
                class="bg-white rounded-2xl shadow-md border p-4 sm:p-6 hover:shadow-lg transform hover:-translate-y-1 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm">Stagiaires</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-[#4B0082] mt-1">{{ $stagiaire ?? 0 }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-green-200 to-green-100 text-[#4B0082]">
                        <i class="fas fa-user-graduate text-lg sm:text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">En formation actuellement</p>
            </div>

        </div>

        <!-- ================= ACTIVITÉ RÉCENTE ================= -->

        <div class="mt-8 bg-white rounded-2xl shadow border">
            <div class="p-4 sm:p-6 border-b">
                <h2 class="text-base sm:text-lg font-semibold text-[#4B0082] flex items-center gap-2">
                    <i class="fas fa-clock"></i> Activité récente
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                <ul class="space-y-4 text-xs sm:text-sm">
                    @forelse($activites ?? [] as $act)
                        <li class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $act['color'] ?? 'bg-green-500' }}"></div>
                                <span class="text-gray-600 flex items-center gap-1">
                                    @if (isset($act['icon']))
                                        <i class="{{ $act['icon'] }}"></i>
                                    @endif
                                    {{ $act['message'] }}
                                </span>
                            </div>
                            @if (isset($act['created_at']))
                                <span class="text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($act['created_at'])->locale('fr')->diffForHumans() }}
                                </span>
                            @endif
                        </li>
                    @empty
                        <li class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            <span class="text-gray-600">Aucun événement enregistré</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

@endsection
