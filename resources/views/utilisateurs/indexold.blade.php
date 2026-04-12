@extends('layouts.admin')

@section('title', 'Grades')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex flex-wrap items-center gap-2 text-sm">

            <!-- Paramètres -->
            <li>
                <a href="{{ route('parametre.index') }}"
                    class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082] transition font-medium">

                    <i class="fas fa-cogs text-xs"></i>
                    Paramètres
                </a>
            </li>

            <!-- Séparateur -->
            <li>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </li>

            <!-- Catégories -->
            <li class="flex items-center gap-1 text-[#4B0082] font-semibold">
                <i class="fas fa-users text-xs"></i>
                Utilisateurs
            </li>

        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center">

        <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
            <i class="fas fa-users"></i>
            Gestion des Utilisateurs
        </h2>

        <button onclick="openModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
            <i class="fas fa-plus"></i> Ajouter grade
        </button>

    </div>

    <hr class="border-t-2 border-[#4B0082] my-6">

    <!-- ================= SEARCH ================= -->



@endsection
