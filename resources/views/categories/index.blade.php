@extends('layouts.admin')

@section('title', 'Categories')

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

                <i class="fas fa-sitemap text-xs"></i>
                <span>Catégories</span>

            </li>

        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center">

        <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
            {{-- <i class="fas fa-medal"></i> --}}

            Gestion des Catégories
        </h2>

        <button onclick="openModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
            <i class="fas fa-plus"></i> Ajouter catégorie
        </button>

    </div>

    <hr class="my-6 border-[#4B0082]">

    <!-- ================= SEARCH ================= -->

    <form method="GET" action="{{ route('parametre.categories.index') }}" class="mt-6 mb-4">

        <div class="flex flex-col md:flex-row gap-3">

            <div class="relative flex-1">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher une catégorie..."
                    class="w-full border rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#4B0082] outline-none">

            </div>

            <div class="flex gap-2">

                <button type="submit" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>

                <a href="{{ route('parametre.categories.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-rotate-left"></i>
                    Réinitialiser
                </a>

            </div>

        </div>

    </form>

    <!-- TABLE -->
    <div class="mt-6 bg-white rounded-2xl shadow border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#4B0082]/5 border-b">
                <tr>
                    <th class="p-4">Catégories</th>
                    <th class="p-4">Grades</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">{{ $cat->nom_categorie }}</td>
                        <td class="p-4">{{ $cat->grade->libelle_grade }}</td>
                        <td class="p-4 text-center space-x-2">
                            <button
                                onclick="editCategorie('{{ $cat->id_categorie }}','{{ $cat->nom_categorie }}','{{ $cat->n_order }}','{{ $cat->id_grade }}')"
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openDeleteModal('{{ $cat->id_categorie }}')"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-12 text-gray-400">

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
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- ================= MODAL FORM ================= -->
    <div id="categorieModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">

            <h3 id="modalTitle" class="text-xl font-semibold mb-4">
                Ajouter Catégorie
            </h3>

            <form id="categorieForm" method="POST">

                @csrf

                <div id="methodContainer"></div>

                <div class="space-y-4">

                    <div>
                        <label class="block mb-1">Nom catégorie</label>

                        <select name="nom_categorie" id="nom_categorie" class="w-full border rounded-xl p-2">
                            <option value="">-- Choisir une catégorie --</option>
                            <option value="Sous-Officier">Sous-Officier</option>
                            <option value="Militaire du Rang">Militaire du Rang</option>
                            <option value="Officier">Officier</option>
                            <option value="Officier du Rang">Officier du Rang</option>
                        </select>
                    </div>

                    <input type="hidden" name="n_order" id="n_order">

                    {{-- <div>
                        <label class="block mb-1">Ordre</label>

                        <input type="number" name="n_order" id="n_order" class="w-full border rounded-xl p-2">
                    </div> --}}

                    <div>
                        <label class="block mb-1">Grade</label>

                        <select name="id_grade" id="id_grade" class="w-full border rounded-xl p-2">

                            <option value="">Sélectionner grade</option>

                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id_grade }}">
                                    {{ $grade->libelle_grade }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">

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

    <!-- ================= DELETE MODAL ================= -->
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-6 rounded-2xl">

            <h3 class="text-red-600 text-lg font-semibold mb-4">
                Confirmer suppression
            </h3>

            <p class="mb-6">Voulez-vous supprimer cette catégorie ?</p>

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

    <!-- ================= SCRIPT ================= -->
    <script>
        function openModal() {

            document.getElementById('categorieForm').action =
                "{{ route('parametre.categories.store') }}";

            document.getElementById('modalTitle').innerText =
                "Ajouter Catégorie";

            document.getElementById('nom_categorie').value = "";
            document.getElementById('n_order').value = "";
            document.getElementById('id_grade').value = "";

            document.getElementById('methodContainer').innerHTML = "";

            showModal();
        }

        function editCategorie(id, nom, order, grade) {

            document.getElementById('categorieForm').action =
                "/parametres/categories/" + id;

            document.getElementById('methodContainer').innerHTML =
                '@method('PUT')';

            document.getElementById('modalTitle').innerText =
                "Modifier Catégorie";

            document.getElementById('nom_categorie').value = nom;
            document.getElementById('n_order').value = order;
            document.getElementById('id_grade').value = grade;

            showModal();
        }

        function openDeleteModal(id) {

            document.getElementById('deleteForm').action =
                "/parametres/categories/" + id;

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
            let modal = document.getElementById('categorieModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            let modal = document.getElementById('categorieModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    <script>
        const categoriesByGrade = {
            18: {
                nom: 'Officier',
                n_order: 1
            },
            17: {
                nom: 'Officier',
                n_order: 2
            },
            16: {
                nom: 'Officier',
                n_order: 3
            },
            15: {
                nom: 'Officier',
                n_order: 4
            },
            14: {
                nom: 'Officier',
                n_order: 5
            },
            13: {
                nom: 'Officier',
                n_order: 6
            },
            12: {
                nom: 'Officier',
                n_order: 7
            },
            11: {
                nom: 'Officier',
                n_order: 8
            },
            10: {
                nom: 'Officier',
                n_order: 9
            },
            9: {
                nom: 'Officier',
                n_order: 10
            },
            8: {
                nom: 'Officier',
                n_order: 11
            },
            7: {
                nom: 'Officier du Rang',
                n_order: 12
            },
            6: {
                nom: 'Officier du Rang',
                n_order: 13
            },
            5: {
                nom: 'Officier du Rang',
                n_order: 14
            },
            4: {
                nom: 'Officier du Rang',
                n_order: 15
            },
            3: {
                nom: 'Officier du Rang',
                n_order: 16
            },
            2: {
                nom: 'Sous-Officier',
                n_order: 17
            },
            1: {
                nom: 'Sous-Officier',
                n_order: 18
            },
            22: {
                nom: 'Militaire du Rang',
                n_order: 19
            },
            21: {
                nom: 'Militaire du Rang',
                n_order: 20
            },
            20: {
                nom: 'Militaire du Rang',
                n_order: 21
            },
            19: {
                nom: 'Militaire du Rang',
                n_order: 22
            }
        };

        const gradeSelect = document.getElementById('id_grade');
        const categorieSelect = document.getElementById('nom_categorie');
        const nOrderField = document.getElementById('n_order');

        gradeSelect.addEventListener('change', function() {
            const gradeId = parseInt(this.value);
            if (categoriesByGrade[gradeId]) {
                categorieSelect.value = categoriesByGrade[gradeId].nom;
                nOrderField.value = categoriesByGrade[gradeId].n_order;
            } else {
                categorieSelect.value = '';
                nOrderField.value = '';
            }
        });
    </script>

@endsection
