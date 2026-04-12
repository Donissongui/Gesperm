@extends('layouts.admin')

@section('title', 'Brigades')

@section('content')
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-sm">

            <!-- Paramètres -->
            <li>
                <a href="{{ route('parametre.index') }}"
                    class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082]
                transition font-medium">

                    <i class="fas fa-cogs text-xs"></i>
                    Paramètres

                </a>
            </li>

            <!-- Séparateur -->
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </li>

            <!-- Page active -->
            <li
                class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold
            flex items-center gap-2 rounded-lg">

                <i class="fas fa-helmet-safety text-xs"></i>
                <span>Brigades</span>

            </li>

        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center">

        <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">

            Gestion des Brigades
        </h2>

        <button onclick="openModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
            <i class="fas fa-plus"></i> Ajouter brigade
        </button>

    </div>

    <hr class="my-6 border-[#4B0082]">
    <form method="GET" class="mt-6 mb-4">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une brigade..."
                    class="w-full border rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#4B0082] outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
                    <i class="fas fa-search"></i> Rechercher
                </button>

                <a href="{{ route('parametre.brigades.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-rotate-left"></i> Réinitialiser
                </a>
            </div>
        </div>
    </form>
    <!-- TABLE -->
    <div class="mt-6 bg-white rounded-2xl shadow border">

        <table class="w-full text-sm">

            <thead class="bg-[#4B0082]/5 border-b">
                <tr>
                    <th class="p-4">Nom Brigade</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($brigades as $brigade)
                    <tr>

                        <td class="p-4 font-medium">
                            {{ $brigade->nom_brigade }}
                        </td>

                        <td class="p-4 text-center">

                            <!-- EDIT -->
                            <button onclick="editBrigade('{{ $brigade->id_brigade }}','{{ $brigade->nom_brigade }}')"
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- DELETE -->
                            <button onclick="openDeleteModal('{{ $brigade->id_brigade }}')"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2" class="text-center p-12 text-gray-400">

                            <div class="flex flex-col items-center justify-center">

                                <div
                                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                                        rounded-full flex items-center justify-center mb-4">

                                    <i class="fas fa-folder-open text-2xl"></i>

                                </div>

                                <p class="text-sm">
                                    Aucune donnée disponible
                                </p>

                            </div>

                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
        <!-- Pagination -->
        <div class="p-4">
            {{ $brigades->links() }}
        </div>
    </div>

    <!-- MODAL FORM -->
    <div id="brigadeModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">

            <h3 id="modalTitle" class="text-xl font-semibold mb-4">
                Ajouter Brigade
            </h3>

            <form id="brigadeForm" method="POST">

                @csrf
                <div id="methodContainer"></div>

                <div class="mb-4">
                    <label class="block mb-1">Nom brigade</label>

                    <input type="text" name="nom_brigade" id="nom_brigade" class="w-full border rounded-xl p-2">
                </div>

                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded-xl">
                        Annuler
                    </button>

                    <button class="bg-[#4B0082] text-white px-4 py-2 rounded-xl">
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function openModal() {

            document.getElementById('brigadeForm').action =
                "{{ route('parametre.brigades.store') }}";

            document.getElementById('modalTitle').innerText =
                "Ajouter Brigade";

            document.getElementById('nom_brigade').value = "";

            document.getElementById('methodContainer').innerHTML = "";

            showModal();
        }

        function editBrigade(id, nom) {

            document.getElementById('brigadeForm').action =
                "/parametres/brigades/" + id;

            document.getElementById('methodContainer').innerHTML =
                '@method('PUT')';

            document.getElementById('modalTitle').innerText =
                "Modifier Brigade";

            document.getElementById('nom_brigade').value = nom;

            showModal();
        }

        function openDeleteModal(id) {

            document.getElementById('deleteForm').action =
                "/parametres/brigades/" + id;

            let modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function showModal() {
            let modal = document.getElementById('brigadeModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            let modal = document.getElementById('brigadeModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

@endsection
