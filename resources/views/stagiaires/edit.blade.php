@extends('layouts.admin')

@section('title', 'Modifier Stagiaire')

@section('content')

    <div class="px-2 sm:px-4 lg:px-2">

        <!-- ================= BREADCRUMB ================= -->

        <nav class="mb-6 overflow-x-auto" aria-label="breadcrumb">
            <ol class="flex items-center gap-2 text-xs sm:text-sm whitespace-nowrap">

                <li>
                    <a href="{{ route('stagiaires.index') }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1 transition-colors">

                        <i class="fas fa-user-graduate"></i>

                        <span class="hidden sm:inline">
                            Gestion des Stagiaires
                        </span>

                        <span class="sm:hidden">
                            Stagiaires
                        </span>

                    </a>
                </li>

                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </li>

                <li
                    class="px-2 sm:px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold
                   flex items-center gap-2 rounded-lg">

                    <i class="fas fa-user-edit"></i>
                    Modifier

                </li>

            </ol>
        </nav>


        <!-- ================= HEADER ================= -->

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                    <i class="fas fa-user-edit"></i>
                    Modifier Stagiaire
                </h2>

                <p class="text-gray-500 text-xs sm:text-sm mt-1">
                    Mise à jour des informations du stagiaire
                </p>
            </div>

            <a href="{{ route('stagiaires.index') }}"
                class="w-full md:w-auto text-center bg-[#4B0082] text-white px-5 py-2 rounded-xl shadow
       hover:bg-[#3a0068] hover:shadow-lg transition">

                <i class="fas fa-arrow-left mr-2"></i>
                Retour

            </a>

        </div>


        <!-- ================= FORM CARD ================= -->

        <div class="mt-6 bg-white rounded-2xl shadow-sm border p-4 sm:p-6">

            <form action="{{ route('stagiaires.update', $personnel->id_personnel) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">

                    <!-- Matricule -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Matricule</label>

                        <input type="text" name="matricule" value="{{ old('matricule', $personnel->matricule) }}"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4B0082] outline-none uppercase">

                        <!-- Message d'erreur pour le matricule -->
                        @error('matricule')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom</label>

                        <input type="text" name="nom" value="{{ old('nom', $personnel->nom) }}"
                            class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none text-sm sm:text-base uppercase"
                            required>
                    </div>


                    <!-- Prénom -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Prénom</label>

                        <input type="text" name="prenom" value="{{ old('prenom', $personnel->prenom) }}"
                            class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none text-sm sm:text-base uppercase"
                            required>
                    </div>


                    <!-- Type personnel -->
                    <div class="hidden">
                        <label class="block text-sm font-medium mb-1">Type de personnel</label>

                        <select
                            class="w-full border rounded-xl px-4 py-2.5 bg-gray-100 cursor-not-allowed text-sm sm:text-base"
                            disabled>

                            <option value="militaire" {{ $personnel->type_personnel == 'militaire' ? 'selected' : '' }}>
                                Militaire
                            </option>

                            <option value="stagiaire" {{ $personnel->type_personnel == 'stagiaire' ? 'selected' : '' }}>
                                Stagiaire
                            </option>

                        </select>

                        <input type="hidden" name="type_personnel" value="{{ $personnel->type_personnel }}">
                    </div>


                    <!-- Grade -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Grade</label>

                        <select name="id_grade"
                            class="w-full border rounded-xl px-4 py-2.5 outline-none
        focus:ring-2 focus:ring-[#4B0082] text-sm sm:text-base"
                            required>

                            <option value="">-- Sélectionner --</option>

                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id_grade }}"
                                    {{ $personnel->id_grade == $grade->id_grade ? 'selected' : '' }}>

                                    {{ $grade->libelle_grade }}

                                </option>
                            @endforeach

                        </select>
                    </div>


                    <!-- Brigade -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Brigade</label>

                        <select name="id_brigade"
                            class="w-full border rounded-xl px-4 py-2.5 outline-none
        focus:ring-2 focus:ring-[#4B0082] text-sm sm:text-base"
                            required>

                            <option value="">-- Sélectionner --</option>

                            @foreach ($brigades as $brigade)
                                <option value="{{ $brigade->id_brigade }}"
                                    {{ $personnel->id_brigade == $brigade->id_brigade ? 'selected' : '' }}>

                                    {{ $brigade->nom_brigade }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>


                <!-- ================= BUTTONS ================= -->

                <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-3">

                    <a href="{{ route('personnels.index') }}"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gray-200 text-gray-700
   hover:bg-gray-300 transition text-center">

                        Annuler
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#4B0082] text-white
        shadow hover:shadow-lg transition flex items-center justify-center gap-2">

                        <i class="fas fa-save"></i>
                        Mettre à jour

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
