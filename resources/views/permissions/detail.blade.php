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

    <div class="px-2 sm:px-4 lg:px-2">

        <!-- ================= BREADCRUMB ================= -->
        <nav class="mb-6 no-print overflow-x-auto">
            <ol class="flex items-center gap-2 text-xs sm:text-sm whitespace-nowrap">

                <li>
                    <a href="{{ route('permissions.index') }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">
                        <i class="fas fa-user-clock text-xs"></i> Permissions
                    </a>
                </li>

                <li class="text-gray-400">></li>

                <li>
                    <a href="{{ route('permissions.liste', ['type' => $posseders->first()->personnel->type_personnel]) }}"
                        class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1">

                        @if ($posseders->first()->personnel->type_personnel == 'militaire')
                            <i class="fas fa-person-military-rifle text-xs"></i>
                        @else
                            <i class="fas fa-user-graduate text-xs"></i>
                        @endif

                        Liste Permissions {{ ucfirst($posseders->first()->personnel->type_personnel) }}
                    </a>
                </li>

                <li class="text-gray-400">></li>

                <li class="px-2 sm:px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] rounded-lg flex items-center gap-2">
                    <i class="fas fa-eye text-xs"></i> Détail
                </li>

            </ol>
        </nav>

        <!-- ================= HEADER ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-[#4B0082]">
                Détail Permission - {{ ucfirst($posseders->first()->personnel->type_personnel) }}
            </h2>

            <p class="text-gray-500 text-xs sm:text-sm">
                Informations sur la permission et les personnels associés
            </p>
        </div>

        <!-- ================= DETAILS PERMISSION ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div><strong>Type :</strong> {{ $posseders->first()->personnel->type_personnel }}</div>
            <div><strong>Tranche :</strong> {{ $posseders->first()->permission->tranche }}</div>

            <div>
                <strong>Créée le :</strong>
                {{ \Carbon\Carbon::parse($posseders->first()->created_at)->format('d/m/Y') }}
            </div>

            <div>
                <strong>Heure :</strong>
                {{ \Carbon\Carbon::parse($posseders->first()->created_at)->format('H:i') }}
            </div>

        </div>

        <!-- ================= TABLE PERSONNELS ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6">

            <h3 class="text-base sm:text-lg font-semibold text-[#4B0082] mb-4 flex items-center gap-2">
                <i class="fas fa-users"></i> Personnels associés
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[600px] border rounded-lg">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-2 text-left" hidden>Categorie</th>
                            <th class="p-2 text-left">Grade</th>
                            <th class="p-2 text-left">Nom</th>
                            <th class="p-2 text-left">Prénom</th>
                            <th class="p-2 text-left">Matricule</th>
                            <th class="p-2 text-left">Début</th>
                            <th class="p-2 text-left">Fin</th>
                            <th class="p-2 text-left">Motif</th>
                            <th class="p-2 text-left">Destination</th>
                            <th class="p-2 text-center no-print">Imprimé</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($posseders as $posseder)
                            <tr class="border-t hover:bg-gray-50">

                                <td class="p-2" hidden>
                                    {{ $posseder->personnel->grade->categories->first()?->nom_categorie ?? 'Non défini' }}
                                </td>
                                <td class="p-2">{{ $posseder->personnel->grade->libelle_grade }}</td>
                                <td class="p-2">{{ $posseder->personnel->nom }}</td>
                                <td class="p-2">{{ $posseder->personnel->prenom }}</td>
                                <td class="p-2">{{ $posseder->personnel->matricule }}</td>

                                <td class="p-2">
                                    {{ $posseder->date_début ? \Carbon\Carbon::parse($posseder->date_début)->format('d/m/Y') : 'Non défini' }}
                                </td>

                                <td class="p-2">
                                    {{ $posseder->date_fin ? \Carbon\Carbon::parse($posseder->date_fin)->format('d/m/Y') : 'Non défini' }}
                                </td>
                                <td class="p-2">
                                    {{ $posseder->motif->libelle_motif ?? 'Non défini' }}
                                </td>

                                <td class="p-2">
                                    {{ $posseder->ville->nom_ville ?? 'Non défini' }}
                                </td>

                                <td class="p-2 text-center no-print">
                                    @php
                                        $dernierAvis = $posseder->permission->avisPermissions->last();
                                    @endphp

                                    @if (auth()->user()?->type == 'admin')
                                        {{-- ADMIN : toujours accès --}}
                                        <button onclick="printRow(this)"
                                            class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    @elseif (auth()->user()?->personnel?->service === 'Groupement Stagiaire')
                                        {{-- UTILISATEUR GS : condition stricte --}}
                                        @if (
                                            $dernierAvis &&
                                                $dernierAvis->personnel &&
                                                $dernierAvis->personnel->fonction->nom_fonction == 'COMMANDANT CIT' &&
                                                $dernierAvis->avis == 'favorable')
                                            <button onclick="printRow(this)"
                                                class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        @else
                                            Non disponible
                                        @endif
                                    @else
                                        {{-- AUTRES UTILISATEURS : accès libre --}}
                                        <button onclick="printRow(this)"
                                            class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    @endif


                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="p-6 text-center text-gray-400">
                                    Aucun personnel associé
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
                @if (
                    $dernierAvis &&
                        $dernierAvis->personnel &&
                        $dernierAvis->personnel->fonction->nom_fonction == 'COMMANDANT CIT' &&
                        $dernierAvis->avis == 'favorable')
                    @if ($posseders->count() > 1)
                        <div class="text-right mt-4">
                            <button onclick="printAllRows()"
                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 no-print text-sm">
                                <i class="fas fa-print text-xs"></i> Imprimer toutes les permissions
                            </button>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>


    <script>
        function printRow(button) {

            let row = button.closest("tr");
            let cells = row.querySelectorAll("td");
            let logoUrl = '{{ asset('images/citlog.png') }}';

            generatePrint([cells], logoUrl);

        }


        function printAllRows() {

            let rows = document.querySelectorAll("tbody tr");
            let logoUrl = '{{ asset('images/citlog.png') }}';

            let all = [];

            rows.forEach(row => {
                all.push(row.querySelectorAll("td"));
            });

            generatePrint(all, logoUrl);

        }


        function generatePrint(rows, logoUrl) {

            let permissions = "";

            rows.forEach(cells => {

                permissions += `

<div class="permission">

<div class="table-container">

<div class="col-left">

<div class="box" style="text-align:center">

<p><b>Un permis donnant droit à son titulaire au tarif ferroviaire militaire pour les distances spécifiées.</b></p>

<p><b>(Avec un billet pour le spectacle)</b></p>

<hr class="separator">

<p><b>Deuxième degré</b></p>

</div>

<div class="box" style="text-align:justify">

<p><b>-1-</b> Ce document doit être présenté chaque fois que les membres de la Gendarmerie royale, de la Sécurité nationale ou les agents des transports ferroviaires le demandent.</p>

<p><b>-2-</b> En cas de mobilisation ou de convocation des bénéficiaires, le titulaire de la licence doit rejoindre son unité sans attendre une convocation individuelle.</p>

<p><b>-3-</b> Si le bénéficiaire est hospitalisé, la période d'hospitalisation est décomptée de son congé.</p>

</div>

<p style="font-size:11px;text-align:center"><b>Formulaire 24/3 / QMM</b></p>

</div>


<div class="col-right">

<div class="header-right">

<div class="logo">
<img src="${logoUrl}">
<p><b>${cells[0].innerText}</b></p>
</div>

<div class="header-text">
<p><b>Royaume du Maroc</b></p>
<p>Forces armées royales</p>
<p>Garnison militaire : Kénitra</p>
<p>Unité : Centre d'Instruction des Transmissions</p>
</div>

</div>

<div class="permission-info">

<p class="lined title" style="border-bottom:1px dashed #000;"><b>${cells[7].innerText}</b></p>

<p class="lined" style="border-bottom:1px dashed #000;">
Nom personnel et nom de famille : <b>${cells[2].innerText} ${cells[3].innerText}</b>
</p>

<p class="lined" style="border-bottom:1px dashed #000;">Grade : <b>${cells[1].innerText}</b></p>

<p class="lined" style="display:flex;gap:30px;border-bottom:1px dashed #000;">
<span>Valable à partir du :</span>
<span><b>${cells[5].innerText}</b></span>
<span>au</span>
<span><b>${cells[6].innerText}</b></span>
<span>intégré</span>
</p>

<p class="lined" style="display:flex;gap:30px;border-bottom:1px dashed #000;">
<span>Partir de :</span>
<span><b>Kénitra</b></span>
<span>à :</span>
<span><b>${cells[8].innerText}</b></span>
</p>

<p class="lined" style="border-bottom:1px dashed #000;">
À Kénitra le : <b>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</b>
</p>

<div class="signature-block">

<div class="signature-text">

<p>
Le Colonel Major Samir Didi<br>
Commandant du Centre d'Instruction et des Transmissions<br>
Pour les Forces Armées Royales
</p>

<p class="signature-name">
Signature : <b>S Didi</b>
</p>

</div>

</div>

</div>

</div>

</div>

</div>

`;

            });


            let content = `

<html>

<head>

<style>

@page{
size:A5 landscape;
margin:0;
}

body{
font-family:Arial;
margin:5mm;
}

.permission{
padding-bottom:20px;
margin-bottom:30px;
border-bottom:1px dashed #000;
}

.table-container{
display:flex;
}

.col-left{
width:30%;
padding-right:10px;
border-right:1px solid #000;
font-size:12px;
line-height:1.4;
}

.col-right{
width:70%;
padding-left:15px;
}

.logo img{
height:80px;
}

.logo{
text-align:center;
margin-bottom:10px;
width:66%;
}

.logo p{
margin:8px 0;
font-weight:bold;
font-size:15px;
}

.header-right{
display:flex;
align-items:center;
gap:10px;
margin-bottom:10px;
}

.header-text{
font-family:Georgia,"Times New Roman",serif;
font-style:italic;
font-size:12px;
line-height:1.2;
width:34%;
text-align:center;
}

.header-text p{
margin:2px 0;
}

.box{
border:1px solid black;
padding:6px;
margin-bottom:8px;
}

.separator{
border-top:1px solid black;
margin:6px 0;
}

.permission-info{
font-size:13px;
line-height:1.6;
margin-top:10px;
}

.permission-info p{
margin:10px 0;
}

.permission-info p.lined{
border-bottom:1px solid #000;
padding-bottom:4px;
}

.permission-info p.title{
font-size:18px;
text-align:center;
font-weight:bold;
margin-bottom:16px;
}

.signature-block{
display:flex;
justify-content:flex-end;
margin-top:20px;
}

.signature-text{
text-align:center;
width:200px;
font-size:11px;
}

.signature-name{
margin-top:8px;
font-weight:bold;
}

</style>

</head>

<body>

${permissions}

<script>

setTimeout(()=>{
window.print();
},500);

<\/script>

</body>

</html>

`;

            let printWindow = window.open('', '', 'width=1000,height=700');

            printWindow.document.write(content);
            printWindow.document.close();

        }
    </script>
@endsection
