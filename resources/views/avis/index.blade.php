@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-2xl shadow border p-6">

        <h2 class="text-xl font-bold text-[#4B0082] mb-6 flex items-center gap-2">
            <i class="fas fa-history"></i>
            Historique des avis
        </h2>


        <div class="overflow-x-auto">

            <table class="w-full border text-sm">

                <thead class="bg-gray-100">
                    <tr>

                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Permission</th>
                        <th class="p-3 text-left">Motif</th>
                        <th class="p-3 text-left">Avis</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Heure</th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($avis as $index => $a)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3">
                                {{ $index + 1 }}
                            </td>

                            <td class="p-3">
                                {{ $a->permission->type_permission ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $a->permission->posseders->first()->motif->libelle_motif ?? '' }}
                                {{ $a->personnel->prenoms ?? '' }}
                            </td>

                            <td class="p-3">

                                <span
                                    class="px-3 py-1 rounded-lg text-xs
{{ $a->avis == 'favorable' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">

                                    {{ $a->avis }}

                                </span>

                            </td>

                            <td class="p-3">
                                {{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') }}
                            </td>

                            <td class="p-3">
                                {{ \Carbon\Carbon::parse($a->updated_at)->format('H:i') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center p-10 text-gray-400">
                                Aucun avis enregistré
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
@endsection
