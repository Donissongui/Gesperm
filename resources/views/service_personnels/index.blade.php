@extends('layouts.admin')

@section('title', 'Services')

@section('content')

    <!-- ================= BREADCRUMB ================= -->

    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm">

            <li>
                <a href="{{ route('parametre.index') }}" class="flex items-center gap-1 text-gray-500 hover:text-[#4B0082]">

                    <i class="fas fa-cogs text-xs"></i>
                    Paramètres
                </a>
            </li>

            <li class="text-gray-400">
                <i class="fas fa-chevron-right text-xs"></i>
            </li>

            <li class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold flex items-center gap-2 rounded-lg">

                <i class="fas fa-building text-xs"></i>
                Services

            </li>

        </ol>
    </nav>

    <!-- ================= HEADER ================= -->

    <div class="flex justify-between items-center">

        <h2 class="text-2xl font-bold text-[#4B0082]">
            Gestion des Services
        </h2>

        <button onclick="openModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl hover:bg-[#3b0068] transition">

            <i class="fas fa-plus"></i>
            Ajouter service
        </button>

    </div>

    <hr class="my-6 border-[#4B0082]">

    <!-- ================= SEARCH ================= -->

    <form method="GET" class="mt-6 mb-4">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un service..."
                    class="w-full border rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#4B0082] outline-none">

            </div>


            <div class="flex gap-2">
                <button type="submit" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl">
                    <i class="fas fa-search"></i> Rechercher
                </button>

                <a href="{{ route('parametre.services.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-rotate-left"></i> Réinitialiser
                </a>
            </div>
        </div>
    </form>

    <!-- ================= TABLE ================= -->

    <div class="bg-white rounded-2xl shadow border">

        <table class="w-full text-sm">

            <thead class="bg-[#4B0082]/5 border-b">

                <tr>

                    <th class="p-4 text-left">
                        Nom Service
                    </th>

                    <th class="p-4 text-center">
                        Action
                    </th>

                </tr>

            </thead>


            <!-- TABLE BODY -->
            <tbody class="divide-y">
                @forelse($services as $service)
                    <tr>
                        <td class="p-4 font-medium">{{ $service->nom_service }}</td>
                        <td class="p-4 text-center space-x-2">
                            <button onclick="editService('{{ $service->id_service }}','{{ $service->nom_service }}')"
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="openDeleteModal('{{ $service->id_service }}')"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center p-12 text-gray-400">

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
        <!-- PAGINATION -->
        <div class="p-4">
            {{ $services->links() }}
        </div>
    </div>

    <!-- ================= MODAL ADD / EDIT ================= -->

    <div id="serviceModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl">

            <h3 id="modalTitle" class="text-xl font-semibold mb-4 text-[#4B0082]">

                Ajouter Service

            </h3>

            <form id="serviceForm" method="POST">

                @csrf

                <div id="methodContainer"></div>

                <div class="mb-4">

                    <label class="block mb-1 text-sm">
                        Nom service
                    </label>

                    <input type="text" name="nom_service" id="nom_service"
                        class="w-full border rounded-xl p-2 focus:ring-[#4B0082] focus:border-[#4B0082]"
                        oninput="this.value = this.value
       .toLowerCase()
       .replace(/\s+/g,' ')
       .replace(/\b\w/g, l => l.toUpperCase())"
                        required>

                </div>

                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded-xl">

                        Annuler

                    </button>

                    <button class="bg-[#4B0082] text-white px-4 py-2 rounded-xl hover:bg-[#3b0068]">

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= MODAL DELETE ================= -->

    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl">

            <div class="flex items-center gap-3 text-red-600 mb-4">

                {{-- <i class="fas fa-exclamation-triangle text-xl"></i> --}}

                <h3 class="text-lg font-semibold">
                    Confirmation suppression
                </h3>

            </div>

            <p class="text-gray-600 mb-6">

                Voulez-vous vraiment supprimer ce service ?

            </p>

            <form id="deleteForm" method="POST">

                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 rounded-xl">

                        Annuler

                    </button>

                    <button class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">

                        Supprimer

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= SCRIPT ================= -->

    <script>
        function openModal() {

            document.getElementById('serviceForm').action =
                "{{ route('parametre.services.store') }}";

            document.getElementById('modalTitle').innerText =
                "Ajouter Service";

            document.getElementById('nom_service').value = "";

            document.getElementById('methodContainer').innerHTML = "";

            showModal();
        }

        function editService(id, nom) {

            document.getElementById('serviceForm').action =
                "/parametres/services/" + id;

            document.getElementById('methodContainer').innerHTML =
                '@method('PUT')';

            document.getElementById('modalTitle').innerText =
                "Modifier Service";

            document.getElementById('nom_service').value = nom;

            showModal();
        }

        function showModal() {

            let modal = document.getElementById('serviceModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }

        function closeModal() {

            let modal = document.getElementById('serviceModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }

        /* ================= DELETE ================= */

        function openDeleteModal(id) {

            document.getElementById('deleteForm').action =
                "/parametres/services/" + id;

            let modal = document.getElementById('deleteModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }

        function closeDeleteModal() {

            let modal = document.getElementById('deleteModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }
    </script>

@endsection
