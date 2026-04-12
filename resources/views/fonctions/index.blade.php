@extends('layouts.admin')

@section('title', 'Fonctions')

@section('content')

    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex flex-wrap items-center gap-2 text-sm">

            <li>
                <a href="{{ route('parametre.index') }}"
                    class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082] transition font-medium">
                    <i class="fas fa-cogs text-xs"></i>
                    Paramètres
                </a>
            </li>

            <li class="flex items-center">
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </li>

            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold flex items-center gap-2 rounded-lg">
                <i class="fas fa-briefcase text-xs"></i>
                <span>Fonctions</span>
            </li>

        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center">

        <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
            Gestion des Fonctions
        </h2>

        <button onclick="openModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
            <i class="fas fa-plus"></i> Ajouter fonction
        </button>

    </div>

    <hr class="my-6 border-[#4B0082]">

    <!-- SEARCH -->
    <form method="GET" class="mt-6 mb-4">

        <div class="flex flex-col md:flex-row gap-3">

            <div class="relative flex-1">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher une fonction..."
                    class="w-full border rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#4B0082] outline-none">

            </div>

            <div class="flex gap-2">

                <button type="submit" class="bg-[#4B0082] text-white px-5 rounded-xl flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>

                <a href="{{ route('parametre.fonctions.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 rounded-xl flex items-center gap-2">
                    <i class="fas fa-rotate-left"></i>
                    Réinitialiser
                </a>

            </div>

        </div>

    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow border overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-[#4B0082]/5 border-b">
                <tr>
                    <th class="p-4">Fonction</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($fonctions as $fonction)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 font-medium">
                            {{ $fonction->nom_fonction }}
                        </td>

                        <td class="p-4 text-center space-x-2">

                            <button onclick='editFonction({{ $fonction->id_fonction }}, @json($fonction->nom_fonction))'
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button onclick="openDeleteModal('{{ $fonction->id_fonction }}')"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2" class="text-center p-12 text-gray-400">

                            <div class="flex flex-col items-center">

                                <div
                                    class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082] rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-2xl"></i>
                                </div>

                                <p class="text-sm">
                                    Aucune fonction disponible
                                </p>

                            </div>

                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

        <div class="p-4">
            {{ $fonctions->links() }}
        </div>

    </div>

    <!-- MODAL -->
    <div id="fonctionModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">

            <h3 id="modalTitle" class="text-xl font-semibold mb-4">
                Ajouter Fonction
            </h3>

            <form id="fonctionForm" method="POST">

                @csrf
                <div id="methodContainer"></div>

                <div class="mb-4">

                    <label class="block mb-1">Nom fonction</label>

                    <input type="text" name="nom_fonction" id="nom_fonction"
                        class="w-full border rounded-xl p-2 capitalize">

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

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-6 rounded-2xl">

            <h3 class="text-red-600 text-lg font-semibold mb-4">
                Confirmer suppression
            </h3>

            <p class="mb-6 text-gray-600">
                Voulez-vous supprimer cette fonction ?
            </p>

            <form id="deleteForm" method="POST">

                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-200 px-4 py-2 rounded-xl">
                        Annuler
                    </button>

                    <button class="bg-red-600 text-white px-4 py-2 rounded-xl">
                        Supprimer
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('fonctionForm').action = "{{ route('parametre.fonctions.store') }}";
            document.getElementById('modalTitle').innerText = "Ajouter Fonction";
            document.getElementById('nom_fonction').value = "";
            document.getElementById('methodContainer').innerHTML = "";
            showModal();
        }

        function editFonction(id, nom_fonction) {
            document.getElementById('fonctionForm').action = "/parametres/fonctions/" + id;
            document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modalTitle').innerText = "Modifier Fonction";
            document.getElementById('nom_fonction').value = nom_fonction;
            showModal();
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "/parametres/fonctions/" + id;
            let modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            let modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function showModal() {
            let modal = document.getElementById('fonctionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            let modal = document.getElementById('fonctionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

@endsection
