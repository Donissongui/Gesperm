@extends('layouts.admin')

@section('content')
    <nav class="mb-6" aria-label="breadcrumb">

        <ol class="flex flex-wrap items-center gap-2 text-sm">

            <!-- Permission -->
            <li>
                <a href="{{ route('permissions.index') }}"
                    class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082] transition">

                    <i class="fas fa-user-clock text-xs"></i>
                    Permission
                </a>
            </li>

            <li class="text-gray-400">/</li>

            <!-- Historique -->
            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">

                <i class="fas fa-history text-xs"></i>
                Historique

            </li>

        </ol>

    </nav>
    <div class="bg-white rounded-2xl shadow border p-6">

        <h2 class="text-xl font-bold text-[#4B0082] mb-2 flex items-center gap-2">

            Historique des permissions
        </h2>

        <p class="text-gray-500 text-sm mb-6">
            Consultation des anciennes permissions
        </p>


        <!-- ================= SEARCH ================= -->
        <div class="mb-4">
            <input type="text" id="searchHistorique" placeholder="Rechercher matricule, nom, type, destination..."
                class="w-full border rounded-lg p-2 text-sm focus:ring-[#4B0082] focus:border-[#4B0082]">
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
                        <th class="p-3 text-left">Avis</th>
                        <th class="p-3 text-left">Etat</th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($permissions as $p)
                        <tr class="border-t hover:bg-gray-50 transition historique-row"
                            data-search="{{ strtolower(
                                ($p->personnel->matricule ?? '') .
                                    ' ' .
                                    ($p->personnel->nom ?? '') .
                                    ' ' .
                                    ($p->personnel->prenom ?? '') .
                                    ' ' .
                                    ($p->permission->type_permission ?? '') .
                                    ' ' .
                                    ($p->destination ?? ''),
                            ) }}">

                            <td class="p-3">
                                {{ $p->personnel->matricule ?? '' }}
                            </td>

                            <td class="p-3">
                                {{ $p->personnel->nom ?? '' }}
                                {{ $p->personnel->prenom ?? '' }}
                            </td>

                            <td class="p-3">
                                {{ $p->permission->type_permission ?? '' }}
                            </td>

                            <td class="p-3">
                                {{ $p->date_début }}
                            </td>

                            <td class="p-3">
                                {{ $p->date_fin }}
                            </td>

                            <td class="p-3">
                                {{ $p->ville->nom_ville }}
                            </td>

                            <td class="p-3">
                                {{ $p->avis }}
                            </td>

                            <td class="p-3 font-medium">
                                <span
                                    class="px-3 py-1 rounded-xl text-xs
    {{ $p->statut === 'en cours' ? 'bg-blue-100 text-blue-800' : '' }}
    {{ $p->statut === 'terminée' ? 'bg-green-100 text-green-800' : '' }}
    {{ $p->statut === 'expirée' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $p->statut }}
                                </span>

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

    </div>


    <!-- ================= SCRIPT LIVE SEARCH ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchHistorique');
            const rows = document.querySelectorAll('.historique-row');

            if (!searchInput) return;

            searchInput.addEventListener('keyup', function() {

                const filter = searchInput.value.toLowerCase();

                rows.forEach(row => {

                    const text = row.dataset.search;

                    row.style.display = text.includes(filter) ? '' : 'none';

                });

            });

        });
    </script>
@endsection
