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
                        <th class="p-3 text-left">Avis attendu</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $index => $permission)
                        @php
                            $ordreUser = match (auth()->user()->type) {
                                'avis1' => 1,
                                'avis2' => 2,
                                'avis3' => 3,
                                default => null,
                            };
                            $avis = $permission->avisPermissions->where('ordre', $ordreUser)->first();
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

                        <tr data-permission-id="{{ $permission->id }}" data-avis-id="{{ $avis?->id }}"
                            data-posseders='@json($possedersData)'>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $permission->type_permission }}</td>
                            <td class="p-3">{{ optional($permission->posseders->first()->motif)->libelle_motif ?? '-' }}
                            </td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($permission->created_at)->format('H:i') }}</td>
                            <td class="p-3">
                                <span
                                    class="px-3 py-1 rounded-xl text-xs {{ $avis && $avis->avis ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $avis?->avis ?? 'En attente' }}
                                </span>
                            </td>
                            <td class="p-3 text-center flex justify-center gap-2">
                                <a href="#"
                                    class="p-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition"
                                    title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if (!$avis || $avis->avis == 'en attente')
                                    <a href="#"
                                        class="btn-traiter-avis p-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition"
                                        title="Traiter l'avis" data-permission-id="{{ $permission->id }}">
                                        <i class="fas fa-check"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-12 text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-16 h-16 bg-[#4B0082]/10 text-[#4B0082] rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-folder-open text-2xl"></i>
                                    </div>
                                    <p class="text-sm">Aucune permission en attente pour vous</p>
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

    <!-- Modal avis -->
    <div id="avisModal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-md relative shadow-xl">
            <h3 class="text-xl font-bold text-[#4B0082] mb-4 flex items-center gap-2">
                <i class="fas fa-check"></i> Donner votre avis
            </h3>

            <form id="avisForm">
                @csrf
                <input type="hidden" name="id_avis" id="avisId">
                <div class="mb-4">
                    <label for="avis" class="block text-sm font-medium text-gray-700">Avis</label>
                    <select name="avis" id="avisSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="favorable">Favorable</option>
                        <option value="defavorable">Défavorable</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="closeAvisModalBtn"
                        class="px-4 py-2 rounded-lg border border-gray-300">Annuler</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Envoyer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Modal détails
            document.querySelectorAll('[title="Voir les détails"]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const possederData = JSON.parse(row.dataset.posseders || '[]');

                    let html = '<table class="w-full text-sm border rounded-lg">';
                    html += `<tr class="border-b">
                <th class="p-3 bg-gray-100 text-left">#</th>
                <th class="p-3 bg-gray-100 text-left">Grade</th>
                <th class="p-3 bg-gray-100 text-left">Nom et Prénoms</th>
                <th class="p-3 bg-gray-100 text-left">Date début</th>
                <th class="p-3 bg-gray-100 text-left">Date fin</th>
                <th class="p-3 bg-gray-100 text-left">Motif</th>
                <th class="p-3 bg-gray-100 text-left">Ville</th>
            </tr>`;

                    possederData.forEach((p, index) => {
                        html += `<tr class="border-b">
                    <td class="p-3">${index+1}</td>
                    <td class="p-3">${p.grade}</td>
                    <td class="p-3">${p.nom_et_prenoms}</td>
                    <td class="p-3">${p.date_début}</td>
                    <td class="p-3">${p.date_fin}</td>
                    <td class="p-3">${p.motif}</td>
                    <td class="p-3">${p.ville}</td>
                </tr>`;
                    });

                    html += '</table>';
                    document.getElementById('detailContent').innerHTML = html;
                    document.getElementById('detailModal').classList.remove('hidden');
                });
            });

            document.getElementById('closeDetailModal').onclick = () => {
                document.getElementById('detailModal').classList.add('hidden');
            };

            // Modal avis
            document.querySelectorAll('.btn-traiter-avis').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const avisId = row.dataset.avisId; // ⚡ utilise l'ID réel de AvisPermission
                    document.getElementById('avisId').value = avisId;
                    document.getElementById('avisModal').classList.remove('hidden');
                });
            });

            document.getElementById('closeAvisModalBtn').onclick = () => {
                document.getElementById('avisModal').classList.add('hidden');
            };

            // Soumission AJAX pour modification
            document.getElementById('avisForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("{{ route('avispermissions.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Met à jour le badge avec l'avis choisi
                            const avisRow = document.querySelector(
                                `tr[data-avis-id="${formData.get('id_avis')}"]`
                            );
                            if (avisRow) {
                                avisRow.dataset.avisId = data.avis_id; // ⚡ ID réel après update
                                const badge = avisRow.querySelector('td span');
                                badge.textContent = formData.get('avis');
                                badge.className = `px-3 py-1 rounded-xl text-xs ${
                        formData.get('avis') === 'favorable'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800'
                    }`;
                            }
                            document.getElementById('avisModal').classList.add('hidden');
                            alert(data.message);
                        } else {
                            alert('Erreur lors de l\'envoi de l\'avis.');
                        }
                    })
                    .catch(err => console.error(err));
            });

        });
    </script>
@endsection
