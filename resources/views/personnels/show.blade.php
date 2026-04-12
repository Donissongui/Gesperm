@extends('layouts.admin')

@section('title', 'Détail Personnel')

@section('content')

    <div class="px-2 sm:px-4 lg:px-2">

        <!-- ================= BREADCRUMB ================= -->

        <nav class="mb-6 overflow-x-auto" aria-label="breadcrumb">
            <ol class="flex items-center gap-2 text-xs sm:text-sm whitespace-nowrap">

                <!-- Page parent -->
                <li>
                    <a href="{{ route('personnels.index') }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1 transition-colors">

                        <i class="fas fa-users"></i>

                        <span class="hidden sm:inline">
                            Gestion du Militaire
                        </span>

                        <span class="sm:hidden">
                            Militaires
                        </span>

                    </a>
                </li>

                <!-- Séparateur -->
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </li>

                <!-- Page actuelle -->
                <li
                    class="px-2 sm:px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold
                   flex items-center gap-2 rounded-lg">

                    <i class="fas fa-user"></i>

                    <span>
                        Détail
                    </span>

                </li>

            </ol>
        </nav>


        <!-- ================= HEADER ================= -->

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    Détail du Militaire <p
                        class="inline-block mt-1 px-3 py-1 bg-[#4B0082]/10
                          text-[#4B0082] rounded-full text-xs sm:text-sm
                          font-medium capitalize">

                        {{ $personnel->fonction->nom_fonction ?? '-' }}

                    </p>
                </h2>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Informations complètes du militaire
                </p>
            </div>

            <a href="{{ route('personnels.index') }}"
                class="w-full md:w-auto text-center bg-[#4B0082] text-white px-5 py-2 rounded-xl shadow
       hover:bg-[#3a0068] hover:shadow-lg transition">

                <i class="fas fa-arrow-left mr-2"></i>
                Retour

            </a>

        </div>


        <!-- ================= PROFILE CARD ================= -->

        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 hover:shadow-lg transition">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">

                <!-- LEFT -->
                <div class="space-y-4 sm:space-y-5">

                    <div>
                        <span class="text-gray-400 text-xs uppercase">
                            Matricule
                        </span>

                        <p class="font-semibold mt-1 text-sm sm:text-base">
                            {{ $personnel->matricule ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-400 text-xs uppercase">
                            Nom & Prénom
                        </span>

                        <p class="font-semibold mt-1 text-sm sm:text-base">
                            {{ $personnel->nom ?? '-' }}
                            {{ $personnel->prenom ?? '-' }}
                        </p>
                    </div>



                    <div>
                        <span class="text-gray-400 text-xs uppercase">
                            Type Personnel
                        </span>

                        <p
                            class="inline-block mt-1 px-3 py-1 bg-[#4B0082]/10
                          text-[#4B0082] rounded-full text-xs sm:text-sm
                          font-medium capitalize">

                            {{ $personnel->type_personnel ?? '-' }}

                        </p>
                    </div>

                </div>


                <!-- RIGHT -->
                <div class="space-y-4 sm:space-y-5">

                    <div>
                        <span class="text-gray-400 text-xs uppercase">
                            Grade
                        </span>

                        <p class="font-semibold mt-1 text-sm sm:text-base">
                            {{ optional($personnel->grade)->libelle_grade ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-400 text-xs uppercase">
                            Service
                        </span>

                        <p class="font-semibold mt-1 text-sm sm:text-base">
                            {{ optional($personnel->service)->nom_service ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
