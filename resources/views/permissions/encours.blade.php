@extends('layouts.admin')

@section('content')
    <!-- ================= BREADCRUMB ================= -->
    <nav class="mb-6" aria-label="breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li>
                <a href="{{ route('permissions.index') }}"
                    class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082] transition">
                    <i class="fas fa-user-clock text-xs"></i>
                    Permission
                </a>
            </li>
            <li class="text-gray-400">/</li>
            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">
                <i class="fas fa-hourglass-half text-xs"></i>
                En cours
            </li>
        </ol>
    </nav>

    <!-- ================= CONTAINER ================= -->
    <div class="bg-white rounded-2xl shadow border p-6">
        <h2 class="text-xl font-bold text-[#4B0082] mb-2 flex items-center gap-2">
            Permissions en cours
        </h2>
        <p class="text-gray-500 text-sm mb-4">
            Consultation des permissions actuellement en cours
        </p>

        <!-- ================= SEARCH ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
            <!-- Filtre statut -->
            <select id="statusFilter"
                class="w-full sm:w-1/3 border rounded-lg p-2 text-sm focus:ring-[#4B0082] focus:border-[#4B0082]">
                <option value="all">Tout afficher</option>
                <option value="en cours">En cours</option>
                <option value="expirée">Expirée</option>
            </select>

            <!-- Recherche live -->
            <input type="text" id="searchEncours" placeholder="Rechercher matricule, nom, type, destination..."
                class="w-full sm:w-2/3 border rounded-lg p-2 text-sm focus:ring-[#4B0082] focus:border-[#4B0082]">

        </div>
        <!-- ================= TABLE ================= -->
        <div class="overflow-x-auto">
            <table class="w-full border text-sm rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">Matricule</th>
                        <th class="p-3 text-left">Nom & Prénom</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Date début</th>
                        <th class="p-3 text-left">Date fin</th>
                        <th class="p-3 text-left">Destination</th>
                        <th class="p-3 text-left">Etat</th>
                        <th class="p-3 text-center">Arrivée</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $p)
                        <tr class="border-t hover:bg-gray-50 transition encours-row"
                            data-search="{{ strtolower(($p->personnel->matricule ?? '') . ' ' . ($p->personnel->nom ?? '') . ' ' . ($p->personnel->prenom ?? '') . ' ' . ($p->permission->type_permission ?? '') . ' ' . ($p->destination ?? '')) }}"
                            data-status="{{ $p->statut }}">
                            <td class="p-3">{{ $p->personnel->matricule ?? '' }}</td>
                            <td class="p-3">{{ $p->personnel->nom ?? '' }} {{ $p->personnel->prenom ?? '' }}</td>
                            <td class="p-3">{{ $p->permission->type_permission ?? '' }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($p->date_début)->format('d/m/Y') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($p->date_fin)->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $villes->firstWhere('id_ville', $p->id_ville)?->nom_ville ?? '' }}</td>
                            <td class="p-3 font-medium">
                                <span
                                    class="px-3 py-1 rounded-xl text-xs
        {{ $p->statut == 'en cours' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $p->statut }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                <input type="checkbox" class="arrival-checkbox" data-id="{{ $p->id_posseder }}"
                                    data-matricule="{{ $p->personnel->matricule ?? '' }}"
                                    data-nom="{{ $p->personnel->nom ?? '' }} {{ $p->personnel->prenom ?? '' }}"
                                    data-type="{{ $p->permission->type_permission ?? '' }}"
                                    data-date-debut="{{ \Carbon\Carbon::parse($p->date_début)->format('d/m/Y') }}"
                                    data-date-fin="{{ \Carbon\Carbon::parse($p->date_fin)->format('d/m/Y') }}"
                                    data-destination="{{ $villes->firstWhere('id_ville', $p->id_ville)?->nom_ville ?? '' }}"
                                    {{ $p->arrive ? 'checked disabled' : '' }}>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-12 text-gray-400">

                                <div class="flex flex-col items-center">

                                    <div
                                        class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082]
                            rounded-full flex items-center justify-center mb-4">

                                        <i class="fas fa-folder-open text-2xl"></i>

                                    </div>

                                    <p class="text-sm">
                                        Aucune donnée trouvée
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ================= BOUTON VALIDER ARRIVEES ================= -->
        <div class="mb-4 text-right mt-4">
            <button id="markArrivals"
                class="bg-[#4B0082] text-white px-4 py-2 rounded-xl hover:bg-[#3b0068] transition text-sm shadow">
                <i class="fas fa-check-circle mr-1"></i>
                Valider arrivées
            </button>
        </div>
    </div>

    <!-- ================= MODAL DE CONFIRMATION ================= -->
    <div id="arrivalConfirmModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-4xl relative shadow-xl max-h-[85vh] overflow-y-auto">

            <h3 class="text-xl font-bold text-[#4B0082] mb-3 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                Confirmer les arrivées
            </h3>

            <p id="modalCount" class="mb-4 text-gray-600 font-medium bg-gray-50 border rounded-lg p-3"></p>

            <div class="overflow-x-auto border rounded-xl">
                <table id="modalInfo" class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Matricule</th>
                            <th class="p-3">Nom & Prénom</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Date début</th>
                            <th class="p-3">Date fin</th>
                            <th class="p-3">Destination</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y"></tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button id="cancelModal"
                    class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                    Annuler
                </button>
                <button id="confirmModal" type="button"
                    class="px-4 py-2 rounded-xl bg-[#4B0082] text-white hover:bg-[#3b0068] transition shadow">
                    Confirmer
                </button>
            </div>

            <button id="closeModal"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchEncours');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('.encours-row');
            const markBtn = document.getElementById('markArrivals');
            const modal = document.getElementById('arrivalConfirmModal');
            const modalCount = document.getElementById('modalCount');
            const modalInfoBody = document.querySelector('#modalInfo tbody');
            const cancelBtn = document.getElementById('cancelModal');
            const closeBtn = document.getElementById('closeModal');
            const confirmBtn = document.getElementById('confirmModal');

            let selectedPermissions = [];

            // ===== RECHERCHE =====
            function filterRows() {
                const filter = searchInput.value.toLowerCase();
                const status = statusFilter.value;

                rows.forEach(row => {
                    const matchesSearch = row.dataset.search.includes(filter);
                    const matchesStatus = status === 'all' || row.dataset.status === status;

                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('keyup', filterRows);
            statusFilter.addEventListener('change', filterRows);

            // ===== OUVRIR MODAL =====
            markBtn.addEventListener('click', function() {

                const checkedBoxes = Array.from(document.querySelectorAll('.arrival-checkbox:checked'));
                if (checkedBoxes.length === 0) {
                    alert('Veuillez sélectionner au moins une permission.');
                    return;
                }

                selectedPermissions = checkedBoxes.map(cb => parseInt(cb.dataset.id));

                modalCount.textContent =
                    `Vous êtes sur le point de valider l'arrivée de ${selectedPermissions.length} permission(s).`;

                modalInfoBody.innerHTML = checkedBoxes.map(cb => `
            <tr>
                <td class="p-2 border">${cb.dataset.matricule}</td>
                <td class="p-2 border">${cb.dataset.nom}</td>
                <td class="p-2 border">${cb.dataset.type}</td>
                <td class="p-2 border">${cb.dataset.dateDebut}</td>
                <td class="p-2 border">${cb.dataset.dateFin}</td>
                <td class="p-2 border">${cb.dataset.destination}</td>
            </tr>
        `).join('');

                modal.classList.remove('hidden');
            });

            // ===== FERMER MODAL =====
            [cancelBtn, closeBtn].forEach(btn => {
                btn.addEventListener('click', () => modal.classList.add('hidden'));
            });

            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // ===== CONFIRMER ARRIVÉES =====
            confirmBtn.addEventListener('click', function() {
                if (selectedPermissions.length === 0) return;

                fetch("{{ route('permissions.arrive') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            ids: selectedPermissions
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Erreur HTTP ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // mettre à jour la table côté client
                            selectedPermissions.forEach(id => {
                                const cb = document.querySelector(
                                    `.arrival-checkbox[data-id="${id}"]`);
                                if (cb) {
                                    cb.checked = true;
                                    cb.disabled = true;
                                    const row = cb.closest('tr');
                                    if (row) row
                                        .remove(); // ou juste changer le statut si tu veux garder la ligne
                                }
                            });
                            modal.classList.add('hidden');
                            alert('Arrivées validées avec succès !');
                        } else {
                            alert('Erreur serveur : ' + (data.message || 'Impossible de valider.'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Une erreur est survenue lors de la validation.');
                    });
            });

        });
    </script>
@endsection
