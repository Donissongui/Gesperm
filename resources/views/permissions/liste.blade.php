@extends('layouts.admin')

@section('content')
    <div class="px-2 sm:px-4 lg:px-2">

        <!-- ================= BREADCRUMB ================= -->

        <nav class="mb-6 overflow-x-auto">
            <ol class="flex items-center gap-2 text-xs sm:text-sm whitespace-nowrap">

                <li>
                    <a href="{{ route('permissions.index') }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                        <i class="fas fa-user-clock text-xs"></i>
                        Permission
                    </a>
                </li>

                <li class="text-gray-400">></li>

                <li class="px-2 sm:px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">

                    @if ($type == 'militaire')
                        <i class="fas fa-person-military-rifle text-xs"></i>
                        <span class="hidden sm:inline">Liste Permissions Militaire</span>
                        <span class="sm:hidden">Militaire</span>
                    @else
                        <i class="fas fa-user-graduate text-xs"></i>
                        <span class="hidden sm:inline">Liste Permissions Stagiaire</span>
                        <span class="sm:hidden">Stagiaire</span>
                    @endif

                </li>

            </ol>
        </nav>


        <!-- ================= HEADER ================= -->

        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                <h2 class="text-lg sm:text-xl font-bold text-[#4B0082] flex items-center gap-2">

                    <i class="fas fa-list"></i>

                    Liste des permissions
                    {{ $type == 'militaire' ? 'Militaire' : 'Stagiaire' }}

                </h2>


                <!-- Bouton Nouvelle permission -->

                <a href="{{ route('permissions.create', ['type' => $type]) }}"
                    class="w-full md:w-auto text-center bg-[#4B0082] text-white px-4 py-2 rounded-xl
           hover:bg-[#3a006e] transition flex items-center justify-center gap-2">

                    <i class="fas fa-plus"></i>
                    Nouvelle permission

                </a>

            </div>

        </div>


        <!-- ================= SEARCH ================= -->

        <div class="mb-4">
            <form method="GET" action="{{ route('permissions.liste') }}" class="mb-4">

                <input type="hidden" name="type" value="{{ $type }}">

                <div class="grid md:grid-cols-4 gap-3">

                    <!-- Motif -->
                    <select name="motif" class="border rounded-xl p-2 text-sm">
                        <option value="">-- Motif --</option>

                        @foreach ($motifs as $motif)
                            <option value="{{ $motif->id_motif }}"
                                {{ request('motif') == $motif->id_motif ? 'selected' : '' }}>
                                {{ $motif->libelle_motif }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Date début -->
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                        class="border rounded-xl p-2 text-sm">

                    <!-- Date fin -->
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                        class="border rounded-xl p-2 text-sm">

                    <!-- bouton -->
                    <button class="bg-[#4B0082] text-white rounded-xl px-4 py-2 hover:bg-[#3a006e]">

                        <i class="fas fa-search"></i> Rechercher

                    </button>

                </div>

            </form>

            {{-- <input type="text" id="searchInput" placeholder="Rechercher matricule, nom, destination..."
                class="w-full border rounded-xl p-2.5 text-sm focus:ring-[#4B0082] focus:border-[#4B0082]"> --}}

        </div>


        <!-- ================= TABLE ================= -->

        <div class="bg-white rounded-2xl shadow border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[700px]">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-3 text-left">#</th>
                            <th class="p-3 text-left">Motif</th>
                            <th class="p-3 text-left">Début</th>
                            <th class="p-3 text-left">Fin</th>
                            <th class="p-3 text-left">Cree le</th>
                            <th class="p-3 text-left">Heure</th>
                            <th class="p-3 text-left">Avis</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($permissions as $index => $p)
                            <tr class="border-t hover:bg-gray-50 permission-row"
                                data-search="{{ strtolower($p->type_permission . ' ' . $p->tranche) }}">

                                <td class="p-3">
                                    {{ $index + 1 }}
                                </td>

                                @php
                                    $posseder = $p->posseders->first();
                                @endphp

                                <td class="p-3">
                                    {{ $posseder->motif->libelle_motif ?? '' }}
                                </td>


                                <td class="p-3">
                                    {{ $posseder?->date_début ? \Carbon\Carbon::parse($posseder->date_début)->format('d/m/Y') : '' }}
                                </td>

                                <td class="p-3">
                                    {{ $posseder?->date_fin ? \Carbon\Carbon::parse($posseder->date_fin)->format('d/m/Y') : '' }}
                                </td>

                                <td class="p-3">
                                    {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}
                                </td>

                                <td class="p-3">
                                    {{ \Carbon\Carbon::parse($p->updated_at)->format('H:i') }}
                                </td>

                                <td class="p-3">
                                    @php
                                        $avis = optional($posseder->permission->avisPermissions->last())->avis;
                                    @endphp

                                    @if ($avis == 'en attente')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @elseif($avis == 'favorable')
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Favorable
                                        </span>
                                    @elseif($avis == 'défavorable')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Défavorable
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3">

                                    <div class="flex flex-wrap gap-2">

                                        <a href="{{ route('permissions.detail', $p->id_permission) }}"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition">

                                            <i class="fas fa-eye"></i>

                                        </a>
                                        @php
                                            $premierAvis = $p->avisPermissions->sortBy('ordre')->first();
                                        @endphp

                                        @if ($premierAvis?->avis == 'en attente')
                                            <a href="{{ route('permissions.edit', $p->id_permission) }}"
                                                class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center p-12 text-gray-400">

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


            <!-- ================= PAGINATION ================= -->

            <div class="p-4">
                {{ $permissions->appends(request()->query())->links() }}
            </div>

        </div>

    </div>


    <!-- ================= SCRIPT SEARCH ================= -->

    <script>
        document.getElementById("searchInput")?.addEventListener("keyup", function() {

            let filter = this.value.toLowerCase();

            document.querySelectorAll(".permission-row").forEach(row => {

                row.style.display =
                    row.dataset.search.includes(filter) ? "" : "none";

            });

        });
    </script>
@endsection
