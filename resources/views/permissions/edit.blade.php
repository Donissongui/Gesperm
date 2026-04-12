@extends('layouts.admin')

@section('content')
    <div class="px-2 sm:px-4 lg:px-2">

        <!-- ================= BREADCRUMB ================= -->
        <nav class="mb-6 overflow-x-auto">
            <ol class="flex items-center gap-2 text-xs sm:text-sm whitespace-nowrap">
                <li>
                    <a href="{{ route('permissions.liste', ['type' => $permission->type_permission]) }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">
                        <i class="fas fa-user-clock text-xs"></i> Permissions
                    </a>
                </li>
                <li class="text-gray-400">></li>
                <li>
                    <a href="{{ route('permissions.liste', ['type' => $permission->type_permission]) }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                        @if ($permission->type_permission == 'militaire')
                            <i class="fas fa-person-military-rifle text-xs"></i>
                        @else
                            <i class="fas fa-user-graduate text-xs"></i>
                        @endif

                        Liste Permissions {{ ucfirst($permission->type_permission) }}s
                    </a>
                </li>
                <li class="text-gray-400">></li>
                <li class="px-2 sm:px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">
                    <i class="fas fa-edit text-xs"></i>
                    <span class="hidden sm:inline">Modifier Permission</span>
                    <span class="sm:hidden">Modifier</span>
                </li>
            </ol>
        </nav>

        <!-- ================= HEADER ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <h2 class="text-lg sm:text-xl font-bold text-[#4B0082] flex items-center gap-2">
                    <i class="fas fa-edit"></i> Modifier Permission
                </h2>
                <a href="{{ route('permissions.liste', ['type' => $permission->type_permission]) }}"
                    class="w-full md:w-auto text-center bg-gray-200 text-gray-700 px-4 py-2 rounded-xl
                hover:bg-gray-300 transition flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- ================= FORMULAIRE ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6">
            <form action="{{ route('permissions.update', $permission->id_permission) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Type & Tranche -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type_permission" class="w-full border rounded-lg p-2">
                            <option value="militaire" {{ $permission->type_permission == 'militaire' ? 'selected' : '' }}>
                                Militaire
                            </option>
                            <option value="stagiaire" {{ $permission->type_permission == 'stagiaire' ? 'selected' : '' }}>
                                Stagiaire
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tranche</label>
                        <input type="text" name="tranche" value="{{ $permission->tranche }}"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                </div>

                <!-- Personnels associés -->
                <h3 class="text-md font-semibold text-gray-700 mb-2">Personnels associés</h3>
                <div class="overflow-x-auto max-h-96 mb-4 border rounded-xl">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="p-2 border">Grade</th>
                                <th class="p-2 border">Nom</th>
                                <th class="p-2 border">Prénom</th>
                                <th class="p-2 border">Matricule</th>

                                <th class="p-2 border">Début</th>
                                <th class="p-2 border">Fin</th>
                                <th class="p-2 border">motif</th>
                                <th class="p-2 border">Destination</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posseders as $posseder)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 border">{{ $posseder->personnel->grade->libelle_grade }}</td>
                                    <td class="p-2 border">{{ $posseder->personnel->nom }}</td>
                                    <td class="p-2 border">{{ $posseder->personnel->prenom }}</td>
                                    <td class="p-2 border">{{ $posseder->personnel->matricule }}</td>

                                    <td class="p-2 border">
                                        {{ $posseder->permission->posseders->first()?->date_début ? \Carbon\Carbon::parse($posseder->permission->posseders->first()->date_début)->format('d/m/Y') : 'Non défini' }}
                                    </td>

                                    <td class="p-2 border">
                                        {{ $posseder->permission->posseders->first()?->date_fin ? \Carbon\Carbon::parse($posseder->permission->posseders->first()->date_fin)->format('d/m/Y') : 'Non défini' }}
                                    </td>
                                    <td class="p-2 border">
                                        <select name="personnels[{{ $posseder->id_posseder }}][motif]"
                                            class="w-full border rounded px-2 py-1 text-sm">

                                            <option value="">- Sélectionner un motif -</option>

                                            @foreach ($motifs as $motif)
                                                <option value="{{ $motif->id_motif }}"
                                                    {{ $posseder->id_motif == $motif->id_motif ? 'selected' : '' }}>
                                                    {{ $motif->libelle_motif }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </td>

                                    <td class="p-2 border">
                                        <select name="personnels[{{ $posseder->id_posseder }}][destination]"
                                            class="w-full border rounded px-2 py-1 text-sm">

                                            <option value="">- Sélectionner une ville -</option>

                                            @foreach ($villes as $ville)
                                                <option value="{{ $ville->id_ville }}"
                                                    {{ $posseder->id_ville == $ville->id_ville ? 'selected' : '' }}>
                                                    {{ $ville->nom_ville }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-4">
                    <a href="{{ route('permissions.liste', ['type' => $permission->type_permission]) }}"
                        class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Annuler</a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Enregistrer</button>
                </div>
            </form>
        </div>

    </div>
@endsection
