@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-2xl shadow border p-6">
        <h2 class="text-xl font-bold text-[#4B0082] mb-2 flex items-center gap-2">
            Permissions en attente
        </h2>

        <p class="text-gray-500 text-sm mb-4">
            Consultation des permissions pour lesquelles votre avis est attendu
        </p>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Motif</th>
                        <th class="p-3 text-left">Crée le</th>
                        <th class="p-3 text-left">Heure</th>
                        @if (
                            $avisPermissions->contains(function ($a) {
                                return $a->ordre > 1;
                            }))
                            <th class="p-3 text-left">Avis précédent</th>
                        @endif
                        <th class="p-3 text-left">Votre avis</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($avisPermissions as $index => $avis)
                        @php
                            $permission = $avis->permission;
                            $possedersData = $permission->posseders->map(
                                fn($p) => [
                                    'grade' => $p->personnel->grade->libelle_grade,
                                    'nom_et_prenoms' => $p->personnel->nom . ' ' . $p->personnel->prenom,
                                    'date_début' => $p->date_début
                                        ? \Carbon\Carbon::parse($p->date_début)->format('d/m/Y')
                                        : '-',
                                    'date_fin' => $p->date_fin
                                        ? \Carbon\Carbon::parse($p->date_fin)->format('d/m/Y')
                                        : '-',
                                    'motif' => optional($p->motif)->libelle_motif ?? '-',
                                    'ville' => optional($p->ville)->nom_ville ?? '-',
                                ],
                            );
                        @endphp



                        <tr data-permission-id="{{ $permission->id }}" data-avis-id="{{ $avis->id }}"
                            data-posseders='@json($possedersData)'>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $permission->type_permission }}</td>
                            <td class="p-3">{{ optional($permission->posseders->first()->motif)->libelle_motif ?? '-' }}
                            </td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($permission->created_at)->format('H:i') }}</td>
                            @php
                                $avisPrecedent = null;

                                if ($avis->ordre > 1) {
                                    $avisPrecedent = \App\Models\AvisPermission::where(
                                        'id_permission',
                                        $avis->id_permission,
                                    )
                                        ->where('ordre', $avis->ordre - 1)
                                        ->first();
                                }

                                $avisValue = $avisPrecedent->avis ?? null;
                            @endphp

                            @if ($avis->ordre > 1)
                                <td class="p-3 text-center">

                                    @if ($avisValue == 'favorable')
                                        <span class="px-3 py-1 rounded-xl text-xs bg-green-100 text-green-800">
                                            Favorable
                                        </span>
                                    @elseif($avisValue == 'défavorable')
                                        <span class="px-3 py-1 rounded-xl text-xs bg-red-100 text-red-800">
                                            Défavorable
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-xl text-xs bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @endif
                                    <br>
                                    <strong>
                                        {{ $avisPrecedent->personnel->grade->libelle_grade . ' ' . $avisPrecedent->personnel->nom }}
                                    </strong>


                                </td>
                            @endif
                            <td class="p-3">
                                {{ auth()->user()->personnel->id_service }}
                                {{ $avis->permission->posseders->first()->date_fin }}
                                <span
                                    class="px-3 py-1 rounded-xl text-xs {{ $avis->avis && $avis->avis != 'en attente' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $avis->avis ?? 'En attente' }}
                                </span>
                            </td>

                            <td class="p-3 text-center flex justify-center gap-2">
                                <!-- Voir détails -->
                                <a href="#"
                                    class="btn-detail p-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Modifier avis -->
                                @if (!$avis->avis || $avis->avis == 'en attente')
                                    <a href="#"
                                        class="btn-traiter-avis p-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition"
                                        data-avis-id="{{ $avis->id_avis }}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-12 text-gray-400">

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
        </div>
    </div>

    <!-- Modal détails -->
    <div id="detailModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-5xl relative shadow-xl overflow-auto max-h-[80vh]">
            <h3 class="text-xl font-bold text-[#4B0082] mb-4 flex items-center gap-2">
                <i class="fas fa-eye"></i> Détails de la permission
            </h3>
            <div id="detailContent"></div>
            <button id="closeDetailModal"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
    </div>

    <!-- Modal traitement avis -->
    <div id="avisModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-md relative shadow-xl">
            <h3 class="text-xl font-bold text-[#4B0082] mb-4 flex items-center gap-2">
                <i class="fas fa-check"></i> Traiter l'avis
            </h3>

            <form id="avisForm" method="POST" action="">
                @csrf
                @method('PUT')

                <input type="hidden" name="avis_id" id="avis_id">

                <div class="mb-4">
                    <label for="avis" class="block text-sm font-medium text-gray-700">Votre avis</label>
                    <select name="avis" id="avis" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="favorable">Favorable</option>
                        <option value="défavorable">Défavorable</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="closeAvisModal"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600">
                        Enregistrer
                    </button>
                </div>
            </form>

            <button id="closeAvisModalX"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailModal');
            const detailContent = document.getElementById('detailContent');
            const closeDetailBtn = document.getElementById('closeDetailModal');

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const row = this.closest('tr');
                    const possederData = JSON.parse(row.getAttribute('data-posseders') || '[]');

                    if (!possederData.length) {
                        detailContent.innerHTML =
                            '<p class="text-gray-500">Aucun détail disponible.</p>';
                    } else {
                        let html = '<table class="w-full text-sm border rounded-lg">';
                        html += `<tr class="border-b bg-gray-100">
                            <th class="p-3 text-left">#</th>
                            <th class="p-3 text-left">Grade</th>
                            <th class="p-3 text-left">Nom et Prénoms</th>
                            <th class="p-3 text-left">Date début</th>
                            <th class="p-3 text-left">Date fin</th>
                            <th class="p-3 text-left">Motif</th>
                            <th class="p-3 text-left">Ville</th>
                        </tr>`;

                        possederData.forEach((p, index) => {
                            html += `<tr class="border-b">
                                <td class="p-3">${index + 1}</td>
                                <td class="p-3">${p.grade}</td>
                                <td class="p-3">${p.nom_et_prenoms}</td>
                                <td class="p-3">${p.date_début}</td>
                                <td class="p-3">${p.date_fin}</td>
                                <td class="p-3">${p.motif}</td>
                                <td class="p-3">${p.ville}</td>
                            </tr>`;
                        });

                        html += '</table>';
                        detailContent.innerHTML = html;
                    }

                    detailModal.classList.remove('hidden');
                });
            });

            closeDetailBtn.addEventListener('click', () => detailModal.classList.add('hidden'));

            // Fermer si clic à l’extérieur du modal
            detailModal.addEventListener('click', (e) => {
                if (e.target === detailModal) {
                    detailModal.classList.add('hidden');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avisModal = document.getElementById('avisModal');
            const avisForm = document.getElementById('avisForm');
            const avisIdInput = document.getElementById('avis_id');
            const closeAvisBtns = [
                document.getElementById('closeAvisModal'),
                document.getElementById('closeAvisModalX')
            ];

            // Ouvrir le modal au clic sur un bouton "Traiter avis"
            document.querySelectorAll('.btn-traiter-avis').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const avisId = this.dataset.avisId;
                    avisIdInput.value = avisId;

                    // Mettre à jour l'action du formulaire (à adapter selon tes routes Laravel)
                    avisForm.action =
                        `/avis/${avisId}/update`; // Exemple: route PUT /avis/{id}/update

                    avisModal.classList.remove('hidden');
                });
            });

            // Fermer le modal
            closeAvisBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    avisModal.classList.add('hidden');
                });
            });

            // Fermer si clic à l’extérieur du modal
            avisModal.addEventListener('click', (e) => {
                if (e.target === avisModal) {
                    avisModal.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
