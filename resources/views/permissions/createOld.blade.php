@extends('layouts.admin')

@section('content')
    <!-- ================= BREADCRUMB ================= -->

    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm">

            <li>
                <a href="{{ route('permissions.index') }}" class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                    <i class="fas fa-user-clock text-xs"></i>
                    Permission
                </a>
            </li>

            <li class="text-gray-400">></li>

            @if ($type == 'militaire')
                <li>
                    <a href="{{ route('permissions.liste', ['type' => 'militaire']) }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                        <i class="fas fa-person-military-rifle text-xs"></i>
                        Liste Permissions Militaire
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('permissions.liste', ['type' => 'stagiaire']) }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                        <i class="fas fa-user-graduate text-xs"></i>
                        Liste Permissions Stagiaire
                    </a>
                </li>
            @endif



            <li class="text-gray-400">></li>

            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">

                <i class="fas fa-file-circle-plus text-sm"></i>
                Nouvelle Permission
            </li>

        </ol>
    </nav>


    <!-- ================= HEADER ================= -->

    <div class="bg-white rounded-2xl shadow border p-8 mb-8">

        <h2 class="text-xl font-bold text-[#4B0082]">
            Nouvelle Permission
            {{ $type == 'militaire' ? 'Militaire' : 'Stagiaire' }}
        </h2>

        <p class="text-gray-500 text-sm">
            Formulaire de création de permission
        </p>

    </div>


    <!-- ================= SEARCH ================= -->

    <div class="mb-4">
        <input type="text" id="searchPersonnel" placeholder="Rechercher matricule, nom ou prénom..."
            class="w-full border rounded-lg p-2 text-sm focus:ring-[#4B0082] focus:border-[#4B0082]">
    </div>


    <!-- ================= TABLE LIST ================= -->

    <div class="bg-white rounded-2xl shadow border p-6">

        <h3 class="text-lg font-semibold text-[#4B0082] mb-4 flex items-center gap-2">

            <i class="fas fa-list"></i>

            Liste des {{ $type == 'militaire' ? 'Militaires' : 'Stagiaires' }}

        </h3>


        <div class="overflow-x-auto">

            <table class="w-full border rounded-lg text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-center">
                            <input type="checkbox" id="selectAll"
                                class="rounded border-gray-300 text-[#4B0082] focus:ring-[#4B0082]">
                        </th>

                        <th class="p-3 text-left">Matricule</th>
                        <th class="p-3 text-left">Nom</th>
                        <th class="p-3 text-left">Prénom</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($personnels as $personnel)
                        <tr class="personnel-row hover:bg-gray-50 transition"
                            data-search="{{ strtolower($personnel->matricule . ' ' . $personnel->nom . ' ' . $personnel->prenom) }}">

                            <td class="p-3 text-center">

                                <input type="checkbox" class="personnel-checkbox" value="{{ $personnel->id_personnel }}">

                            </td>

                            <td class="p-3">{{ $personnel->matricule }}</td>
                            <td class="p-3">{{ $personnel->nom }}</td>
                            <td class="p-3">{{ $personnel->prenom }}</td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400">
                                Aucune donnée trouvée
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>


    <!-- ================= BOUTON MODAL ================= -->

    <div class="mt-6 flex justify-end">

        <button onclick="openPermissionModal()"
            class="bg-[#4B0082] text-white px-6 py-2 rounded-xl hover:bg-[#3a006e] transition">

            <i class="fas fa-save"></i>
            Créer Permission
        </button>

    </div>


    <!-- ================= MODAL ================= -->

    <div id="permissionModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4 z-50">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-xl font-bold text-[#4B0082]">
                    Création Permission
                </h2>

                <div class="text-sm text-[#4B0082] font-semibold flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span id="selectedCount">0</span>
                    personnel(s) sélectionné(s)
                </div>

            </div>


            <form action="{{ route('permissions.store') }}" method="POST" onsubmit="prepareSelectedPersonnel()">

                @csrf

                <input type="hidden" name="type_personnel" value="{{ $type }}">

                <input type="hidden" name="type_permission" value="{{ $type }}">
                <input type="hidden" name="personnels" id="selectedPersonnels">


                <div class="mb-4">
                    <label class="block text-sm mb-1">Date début</label>
                    <input type="date" name="date_debut" required class="w-full border rounded-lg p-2">
                </div>


                <div class="mb-4">
                    <label class="block text-sm mb-1">Date fin</label>
                    <input type="date" name="date_fin" class="w-full border rounded-lg p-2">
                </div>


                <div class="mb-4">
                    <label class="block text-sm mb-1">Motif</label>
                    <textarea name="motif" class="w-full border rounded-lg p-2"></textarea>
                </div>


                <div class="mb-4">
                    <label class="block text-sm mb-1">Destination</label>
                    <input type="text" name="destination" class="w-full border rounded-lg p-2">
                </div>


                <div class="mb-4">
                    <label class="block text-sm mb-1">Avis</label>
                    <input type="text" name="avis" class="w-full border rounded-lg p-2">
                </div>


                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closePermissionModal()" class="px-4 py-2 bg-gray-200 rounded-lg">

                        Annuler
                    </button>

                    <button type="submit" class="px-6 py-2 bg-[#4B0082] text-white rounded-lg">

                        Enregistrer
                    </button>

                </div>

            </form>

        </div>
    </div>


    <!-- ================= SCRIPT ================= -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.personnel-checkbox');
            const rows = document.querySelectorAll('.personnel-row');
            const searchInput = document.getElementById('searchPersonnel');


            /* =========================
                SELECT ALL
            ========================= */

            if (selectAll) {

                selectAll.addEventListener('change', function() {

                    checkboxes.forEach(cb => {

                        if (cb.closest('tr').style.display !== 'none') {
                            cb.checked = selectAll.checked;
                        }

                    });

                    updateSelectedCount();

                });

            }


            /* =========================
                INDIVIDUAL CHECKBOX
            ========================= */

            checkboxes.forEach(cb => {

                cb.addEventListener('change', function() {

                    syncSelectAllState();
                    updateSelectedCount();

                });

            });


            /* =========================
                SEARCH LIVE
            ========================= */

            if (searchInput) {

                searchInput.addEventListener('keyup', function() {

                    const filter = searchInput.value.toLowerCase();

                    rows.forEach(row => {

                        row.style.display =
                            row.dataset.search.includes(filter) ? '' : 'none';

                    });

                    syncSelectAllState();
                    updateSelectedCount();

                });

            }

        });


        /* =========================
            SYNC SELECT ALL STATE
        ========================= */

        function syncSelectAllState() {

            const checkboxes =
                document.querySelectorAll('.personnel-checkbox');

            const visibleCheckboxes = [...checkboxes].filter(cb =>
                cb.closest('tr').style.display !== 'none'
            );

            const selectAll = document.getElementById('selectAll');

            if (!selectAll) return;

            if (visibleCheckboxes.length === 0) {
                selectAll.checked = false;
                return;
            }

            selectAll.checked =
                visibleCheckboxes.every(cb => cb.checked);
        }


        /* =========================
            MODAL CONTROL
        ========================= */

        function openPermissionModal() {

            prepareSelectedPersonnel();

            document.getElementById('permissionModal')
                .classList.remove('hidden');
        }

        function closePermissionModal() {

            document.getElementById('permissionModal')
                .classList.add('hidden');
        }


        /* =========================
            COUNT SELECTED
        ========================= */

        function updateSelectedCount() {

            const count =
                document.querySelectorAll('.personnel-checkbox:checked').length;

            const counter = document.getElementById('selectedCount');

            if (counter) {
                counter.textContent = count;
            }

        }


        /* =========================
            PREPARE DATA BEFORE SUBMIT
        ========================= */

        function prepareSelectedPersonnel() {

            let selected = [];

            document.querySelectorAll('.personnel-checkbox:checked')
                .forEach(cb => selected.push(cb.value));

            document.getElementById('selectedPersonnels').value =
                JSON.stringify(selected);

        }
    </script>
@endsection
