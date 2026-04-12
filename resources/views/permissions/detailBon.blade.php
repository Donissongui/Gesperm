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
                    <i class="fas fa-eye text-xs"></i> Détail
                </li>
            </ol>
        </nav>

        <!-- ================= HEADER ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-[#4B0082]">Détail Permission -
                {{ ucfirst($permission->type_permission) }}</h2>
            <p class="text-gray-500 text-xs sm:text-sm">Informations sur la permission et les personnels associés</p>
        </div>

        <!-- ================= DETAILS PERMISSION ================= -->
        <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><strong>Type :</strong> {{ $permission->type_permission }}</div>
            <div><strong>Tranche :</strong> {{ $permission->tranche }}</div>
            <div><strong>Créée le :</strong> {{ \Carbon\Carbon::parse($permission->created_at)->format('d/m/Y') }}</div>
            <div><strong>Heure :</strong> {{ \Carbon\Carbon::parse($permission->created_at)->format('H:i') }}</div>
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
                            <th class="p-2 text-left">Matricule</th>
                            <th class="p-2 text-left">Nom</th>
                            <th class="p-2 text-left">Prénom</th>
                            <th class="p-2 text-left">Début</th>
                            <th class="p-2 text-left">Fin</th>
                            <th class="p-2 text-left">Motif</th>
                            <th class="p-2 text-left">Destination</th>
                            <th class="p-2 text-center no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permission->personnels as $personnel)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">{{ $personnel->matricule }}</td>
                                <td class="p-2">{{ $personnel->nom }}</td>
                                <td class="p-2">{{ $personnel->prenom }}</td>
                                <td class="p-2">
                                    {{ $personnel->pivot->date_début ? \Carbon\Carbon::parse($personnel->pivot->date_début)->format('d/m/Y') : '' }}
                                </td>
                                <td class="p-2">
                                    {{ $personnel->pivot->date_fin ? \Carbon\Carbon::parse($personnel->pivot->date_fin)->format('d/m/Y') : '' }}
                                </td>
                                <td class="p-2">
                                    {{ $motifs->firstWhere('id_motif', $personnel->pivot->id_motif)?->libelle_motif ?? '' }}
                                </td>
                                <td class="p-2">
                                    {{ $villes->firstWhere('id_ville', $personnel->pivot->id_ville)?->nom_ville ?? '' }}
                                </td>
                                <td class="p-2 text-center no-print">
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
                <button onclick="printAllRows()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 no-print">
                    <i class="fas fa-print"></i> Imprimer toutes les permissions
                </button>
            </div>
        </div>

    </div>

    <script>
        function printRow(button) {

            let row = button.closest("tr");
            let cells = row.querySelectorAll("td");

            let logoUrl = '{{ asset('images/logo_home.png') }}';

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
    width:66%; /* col8 */
}

.logo p {
    margin: 8px 0;      /* espace réduit entre texte et image */
    font-weight: bold;   /* texte en gras */
    font-size: 15px;     /* taille compacte */
    line-height: 1.2;    /* hauteur de ligne compacte */
}

.header-right{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
}

.header-text {
    font-family: "Georgia", "Times New Roman", serif; /* belle police classique */
    font-style: italic;     /* texte en italique */
    font-size: 12px;        /* réduit les caractères */
    line-height: 1.2;       /* compact */
    width: 34%;             /* col4 */
    text-align: center;     /* centré */
    margin: 0;              /* supprime marges par défaut */
    padding: 0;             /* supprime padding par défaut */
}

.header-text p {
    margin: 2px 0;  /* réduit l'espace entre les paragraphes */
    line-height: 1.5; /* rend le texte plus compact */
}

.table-right{
    width:100%;
    border-collapse:collapse;
}

.table-right th,
.table-right td{
    border:1px solid black;
    padding:6px;
    text-align:left;
}

/* Cadres */

.box{
    border:1px solid black;
    padding:6px;
    margin-bottom:8px;
}

.separator{
    border:none;
    border-top:1px solid black;
    margin:6px 0;
}

.box p{
    margin:4px 0;
}

.permission-info {
    font-size: 13px;
    line-height: 1.6;
    margin-top: 10px;
}

.permission-info p {
    margin: 10px 0; /* plus d'espace entre chaque ligne */
}

.permission-info p.lined {
    padding-bottom: 4px; /* trait plus visible */
    border-bottom: 1px solid #000;
}

.permission-info p.title {
    font-size: 18px; /* titre plus visible */
    text-align: center;
    margin-bottom: 16px;
    font-weight: bold;
}

.signature-block {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.signature-text {
    text-align: center;
    width: 200px;
    font-size: 11px;
    line-height: 1.3;
}

.signature-text p {
    margin: 2px 0;
}

.signature-name {
    margin-top: 8px;
    font-weight: bold;
}

</style>
</head>

<body>

<div class="table-container">

<!-- Colonne gauche -->
<div class="col-left">

<div class="box" style="text-align:center;">

<p><b>Un permis donnant droit à son titulaire au tarif ferroviaire militaire pour les distances spécifiées.</b></p>
<p><b>(Avec un billet pour le spectacle)</b></p>

<hr class="separator">

<p><b>Deuxième degré</b></p>

</div>

<div class="box" style="text-align: justify;">
<p style="margin-bottom: 10px;"><b>-1-</b> Ce document doit être présenté chaque fois que les membres de la Gendarmerie royale, de la Sécurité nationale ou les agents des transports ferroviaires le demandent.</p>

<p style="margin-bottom: 10px;"><b>-2-</b> En cas de mobilisation ou de convocation des bénéficiaires, le titulaire de la licence doit rejoindre son unité sans attendre une convocation individuelle, sauf s'il est en congé de convalescence.</p>

<p style="margin-bottom: 10px;"><b>-3-</b> Si le bénéficiaire est hospitalisé, la période d'hospitalisation est décomptée de son congé.</p>
</div>

<p style="font-size:11px; text-align:center; "><b>Formulaire 24/3 / QMM</b></p>

</div>

<!-- Colonne droite -->
<div class="col-right">

<div class="header-right">

<div class="logo">
<img id="logoPrint" src="${logoUrl}">
<p><b>Sous-officier</b></p>
</div>

<div class="header-text">
<p><b>Royaume du Maroc</b></p>
<p>Forces armées royales</p>
<p>Garnison militaire : Kénitra</p>
<p>Unité : Centre de formation aux armes de signalisation</p>
<p><b>PA ÊTRE</b></p>
</div>

</div>

<div class="permission-info">

  <!-- Titre de la permission -->
<p class="lined title"><b>${cells[5].innerText}</b></p>

<!-- Nom du personnel -->
<p class="lined">
  Nom personnel et nom de famille : &nbsp;<b>${cells[1].innerText} ${cells[2].innerText}</b>
</p>

<!-- Rang -->
<p class="lined">
  Rang : &nbsp;<b>Assistant</b>
</p>

<!-- Dates de validité -->
<p class="lined" style="display:flex; justify-content:flex-start; gap:30px; margin-bottom:0;">
    <span>Valable à partir de :</span>
    <span><b>${cells[3].innerText}</b></span>
    <span>à</span>
    <span><b>${cells[4].innerText}</b></span>
    <span>intégré</span>
</p>

<!-- Départ et destination -->
<p class="lined" style="display:flex; justify-content:flex-start; gap:50px; margin-bottom:0;">
    <span>Partir de :</span>
    <span><b>Kénitra</b></span>
    <span>à :</span>
    <span><b>${cells[6].innerText}</b></span>
</p>

<!-- Date et lieu -->
<p class="lined">
  À Kénitra le : <b>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</b>
</p>

  <!-- Bloc signature -->
  <div class="signature-block">
    <div class="signature-text">
      <p>
        Le Colonel Major Samir Didi<br>
        Commandant du centre d'entraînement du corps des transmissions<br>
        Pour les Forces Armées Royales
      </p>
      <p class="signature-name">
        Signature : <b>Qan S Didi</b>
      </p>
    </div>
  </div>

</div>

</div>

</div>

<script>

let img = document.getElementById("logoPrint");

img.onload = function(){
    window.print();
}

<\/script>

</body>
</html>
`;

            let printWindow = window.open('', '', 'width=900,height=600');
            printWindow.document.write(content);
            printWindow.document.close();

        }
    </script>


    {{-- pour tous --}}
    <script>
        function printAllRows() {

            let rows = document.querySelectorAll("tbody tr");
            let logoUrl = '{{ asset('images/logo_home.png') }}';

            let permissions = "";

            rows.forEach((row, index) => {

                let cells = row.querySelectorAll("td");

                permissions += `

<div class="permission">

<div class="table-container">

<div class="col-left">

<div class="box" style="text-align:center;">

<p><b>Un permis donnant droit à son titulaire au tarif ferroviaire militaire pour les distances spécifiées.</b></p>
<p><b>(Avec un billet pour le spectacle)</b></p>

<hr class="separator">

<p><b>Deuxième degré</b></p>

</div>

<div class="box" style="text-align: justify;">
<p><b>-1-</b> Ce document doit être présenté chaque fois que les membres de la Gendarmerie royale, de la Sécurité nationale ou les agents des transports ferroviaires le demandent.</p>

<p><b>-2-</b> En cas de mobilisation ou de convocation des bénéficiaires, le titulaire de la licence doit rejoindre son unité sans attendre une convocation individuelle.</p>

<p><b>-3-</b> Si le bénéficiaire est hospitalisé, la période d'hospitalisation est décomptée de son congé.</p>
</div>

<p style="font-size:11px;text-align:center;"><b>Formulaire 24/3 / QMM</b></p>

</div>

<div class="col-right">

<div class="header-right">

<div class="logo">
<img src="${logoUrl}">
<p><b>Sous-officier</b></p>
</div>

<div class="header-text">
<p><b>Royaume du Maroc</b></p>
<p>Forces armées royales</p>
<p>Garnison militaire : Kénitra</p>
<p>Unité : Centre de formation aux armes de signalisation</p>
<p><b>PA ÊTRE</b></p>
</div>

</div>

<div class="permission-info">

<p class="lined title"><b>${cells[5].innerText}</b></p>

<p class="lined">
Nom personnel et nom de famille : <b>${cells[1].innerText} ${cells[2].innerText}</b>
</p>

<p class="lined">
Rang : <b>Assistant</b>
</p>

<p class="lined" style="display:flex;gap:30px;">
<span>Valable à partir de :</span>
<span><b>${cells[3].innerText}</b></span>
<span>à</span>
<span><b>${cells[4].innerText}</b></span>
<span>intégré</span>
</p>

<p class="lined" style="display:flex;gap:50px;">
<span>Partir de :</span>
<span><b>Kénitra</b></span>
<span>à :</span>
<span><b>${cells[6].innerText}</b></span>
</p>

<p class="lined">
À Kénitra le : <b>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</b>
</p>

<div class="signature-block">
<div class="signature-text">
<p>
Le Colonel Major Samir Didi<br>
Commandant du centre d'entraînement du corps des transmissions<br>
Pour les Forces Armées Royales
</p>
<p class="signature-name">
Signature : <b>Qan S Didi</b>
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
size:A4;
margin:10mm;
}

body{
font-family:Arial;
}

.permission{
height:48%;
margin-bottom:10px;
}

/* ton CSS original */

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

.box{
border:1px solid black;
padding:6px;
margin-bottom:8px;
}

.separator{
border-top:1px solid black;
margin:6px 0;
}

.permission-info p.lined{
border-bottom:1px solid #000;
padding-bottom:4px;
}

.permission-info p.title{
font-size:18px;
text-align:center;
font-weight:bold;
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

</style>

</head>

<body>

${permissions}

<script>
window.onload=function(){
window.print();
}
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
