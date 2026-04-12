@extends('layouts.admin')

@section('title', 'Ajouter Personnel')

@section('content')

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                Ajouter un Personnel
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Enregistrement d’un nouveau personnel
            </p>
        </div>

        <a href="{{ route('personnels.index') }}"
            class="w-full md:w-auto text-center bg-[#4B0082] text-white px-5 py-2 rounded-xl shadow
       hover:bg-[#3a0068] hover:shadow-lg transition">

            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>

    </div>

    <!-- FORM CARD -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm border p-6">

        <form action="{{ route('personnels.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Matricule -->
                <div>
                    <label class="block text-sm font-medium mb-1">Matricule</label>
                    <input type="text" name="matricule"
                        class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none"
                        required>
                </div>

                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nom</label>
                    <input type="text" name="nom"
                        class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none"
                        required>
                </div>

                <!-- Prénom -->
                <div>
                    <label class="block text-sm font-medium mb-1">Prénom</label>
                    <input type="text" name="prenom"
                        class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none"
                        required>
                </div>

                <!-- Type personnel -->
                <div>
                    <label class="block text-sm font-medium mb-1">Type de personnel</label>

                    <!-- Select bloqué -->
                    <select class="w-full border rounded-xl px-4 py-2.5 bg-gray-100 cursor-not-allowed" disabled>

                        <option value="militaire" selected>Militaire</option>
                        <option value="stagiaire">Stagiaire</option>

                    </select>

                    <!-- Valeur réellement envoyée au serveur -->
                    <input type="hidden" name="type_personnel" value="militaire">

                </div>

                <!-- Grade -->
                <div>
                    <label class="block text-sm font-medium mb-1">Grade</label>
                    <select name="id_grade"
                        class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none"
                        required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">
                                {{ $grade->libelle_grade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brigade -->
                <div>
                    <label class="block text-sm font-medium mb-1">Brigade</label>
                    <select name="id_brigade"
                        class="w-full border rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#4B0082] outline-none"
                        required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($brigades as $brigade)
                            <option value="{{ $brigade->id }}">
                                {{ $brigade->nom_brigade }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-3">

                <a href="{{ route('personnels.index') }}"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-center">
                    Annuler
                </a>

                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#4B0082] text-white shadow hover:shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

@endsection
