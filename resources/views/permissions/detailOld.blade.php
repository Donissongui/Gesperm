@extends('layouts.admin')

@section('content')
    <style>
        @media print {
            @page {
                size: A5 landscape;
                margin: 10mm;
            }

            nav,
            .no-print {
                display: none;
            }

            body {
                background: white;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 6px;
            }
        }
    </style>

    <!-- ================= BREADCRUMB ================= -->
    <nav class="mb-6 no-print">
        <ol class="flex items-center gap-2 text-sm">
            <li><a href="{{ route('permissions.index') }}"
                    class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1"><i
                        class="fas fa-user-clock text-xs"></i> Permissions</a></li>
            <li class="text-gray-400">></li>
            <li>
                <a href="{{ route('permissions.liste', ['type' => $permission->type_permission]) }}"
                    class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">
                    @if ($permission->type_permission == 'militaire')
                        <i class="fas fa-person-military-rifle text-xs"></i>
                    @else
                        <i class="fas fa-user-graduate text-xs"></i>
                    @endif
                    Liste Permissions {{ ucfirst($permission->type_permission) }}
                </a>
            </li>
            <li class="text-gray-400">></li>
            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">
                <i class="fas fa-eye text-xs"></i> Détail
            </li>
        </ol>
    </nav>

    <!-- ================= HEADER ================= -->
    <div class="bg-white rounded-2xl shadow border p-8 mb-8">
        <h2 class="text-xl font-bold text-[#4B0082]">Détail Permission - {{ ucfirst($permission->type_permission) }}</h2>
        <p class="text-gray-500 text-sm">Informations sur la permission et les personnels associés</p>
    </div>

    <!-- ================= DETAILS PERMISSION ================= -->
    <div class="bg-white rounded-2xl shadow border p-6 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div><strong>Type :</strong> {{ $permission->type_permission }}</div>
            <div><strong>Tranche :</strong> {{ $permission->tranche }}</div>
            <div><strong>Créée le :</strong> {{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</div>
            <div><strong>Heure :</strong> {{ \Carbon\Carbon::parse($permission->created_at)->format('H:i') }}</div>
        </div>
    </div>

    <!-- ================= TABLE PERSONNELS ================= -->
    <div class="bg-white rounded-2xl shadow border p-6">
        <h3 class="text-lg font-semibold text-[#4B0082] mb-4 flex items-center gap-2">
            <i class="fas fa-users"></i> Personnels associés
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full border rounded-lg text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Matricule</th>
                        <th class="p-3 text-left">Nom</th>
                        <th class="p-3 text-left">Prénom</th>
                        <th class="p-3 text-left">Début</th>
                        <th class="p-3 text-left">Fin</th>
                        <th class="p-3 text-left">Motif</th>
                        <th class="p-3 text-center no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permission->personnels as $personnel)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $personnel->matricule }}</td>
                            <td class="p-3">{{ $personnel->nom }}</td>
                            <td class="p-3">{{ $personnel->prenom }}</td>
                            <td class="p-3">{{ $personnel->pivot->date_début ?? '' }}</td>
                            <td class="p-3">{{ $personnel->pivot->date_fin ?? '' }}</td>
                            <td class="p-3">{{ $personnel->pivot->motif ?? '' }}</td>
                            <td class="p-3 text-center no-print">
                                <button onclick="printRow(this)"
                                    class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-400">Aucun personnel associé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function printRow(button) {
            let row = button.closest("tr");
            let cells = row.querySelectorAll("td");

            let content = `
<html>
<head>
<title>Permission Personnel</title>
<style>
@page { size: A5 landscape; margin: 10mm; }
body { font-family: Arial; padding: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid black; padding: 6px; text-align: left; }
h2 { margin-bottom: 20px; }
</style>
</head>
<body>
<h2>Fiche Permission Personnel</h2>
<table>
<tr>
<th>Matricule</th>
<th>Nom</th>
<th>Prénom</th>
<th>Début</th>
<th>Fin</th>
<th>Motif</th>
</tr>
<tr>
<td>${cells[0].innerText}</td>
<td>${cells[1].innerText}</td>
<td>${cells[2].innerText}</td>
<td>${cells[3].innerText}</td>
<td>${cells[4].innerText}</td>
<td>${cells[5].innerText}</td>
</tr>
</table>
</body>
</html>
`;

            let printWindow = window.open('', '', 'width=900,height=600');
            printWindow.document.write(content);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
