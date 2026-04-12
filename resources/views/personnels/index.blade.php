@extends('layouts.admin')

@section('title', 'Personnel')

@section('content')

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-[#4B0082] flex items-center gap-2">
                <i class="fas fa-person-military-rifle"></i>
                Gestion des Militaires
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Liste des militaires enregistrés
            </p>
        </div>

        <a href="{{ route('personnels.create') }}"
            class="w-full md:w-auto text-center bg-[#4B0082] text-white px-5 py-2 rounded-xl shadow
       hover:bg-[#3a0068] hover:shadow-lg transition flex items-center justify-center gap-2">

            <i class="fas fa-plus"></i>
            Ajouter militaire

        </a>

    </div>

    <!-- ================= RECHERCHE ================= -->


    <form method="GET" class="mt-6 bg-white p-4 rounded-2xl shadow-sm border">

        <div class="flex flex-col md:flex-row gap-3 items-center">
            <!-- COMBO SERVICE -->
            <div class="w-full md:w-70">

                {{-- <select name="service"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2
                focus:ring-2 focus:ring-[#4B0082]
                focus:border-[#4B0082] outline-none transition">

                    <option value="">Tous les services</option>

                    @foreach ($services as $service)
                        <option value="{{ $service->id_service }}"
                            {{ request('service') == $service->id_service ? 'selected' : '' }}>
                            {{ $service->nom_service }}
                        </option>
                    @endforeach

                </select> --}}

                <select name="service"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2
    focus:ring-2 focus:ring-[#4B0082]
    focus:border-[#4B0082] outline-none transition">

                    {{-- ADMIN --}}
                    @if (Auth::user()->type == 'admin')
                        <option value="" {{ request('service') == '' ? 'selected' : '' }}>
                            Tous les services
                        </option>
                    @endif

                    @foreach ($services as $service)
                        @if (Auth::user()->type == 'admin' || Auth::user()->personnel->id_service == $service->id_service)
                            <option value="{{ $service->id_service }}"
                                {{ request('service') == $service->id_service ||
                                (Auth::user()->type != 'admin' && Auth::user()->personnel->id_service == $service->id_service)
                                    ? 'selected'
                                    : '' }}>
                                {{ $service->nom_service }}
                            </option>
                        @endif
                    @endforeach

                </select>

            </div>
            <!-- SEARCH INPUT -->
            <div class="relative w-full">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nom, prénom, matricule ou service..."
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2
                focus:ring-2 focus:ring-[#4B0082]
                focus:border-[#4B0082] outline-none transition">

            </div>

        </div>

    </form>

    <!-- ================= TABLE ================= -->

    <div class="mt-6 bg-white rounded-2xl shadow-sm border">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-[#4B0082]/5 border-b">
                    <tr class="text-left text-gray-600">

                        <th class="p-4 hidden sm:table-cell">Grade</th>
                        <th class="p-4">Nom</th>
                        <th class="p-4">Prénom</th>
                        <th class="p-4 hidden md:table-cell">Service</th>
                        <th class="p-4 hidden md:table-cell">Fonction</th>
                        <th class="p-4 text-center">Action</th>

                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($personnels as $personnel)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-4 hidden sm:table-cell text-gray-600">
                                {{ $personnel->grade->libelle_grade ?? '-' }}
                            </td>

                            <td class="p-4 font-medium">
                                {{ $personnel->nom }}
                            </td>

                            <td class="p-4">
                                {{ $personnel->prenom }}
                            </td>

                            <td class="p-4 hidden md:table-cell text-gray-600">
                                {{ $personnel->service->nom_service ?? '-' }}

                            </td>

                            <td class="p-4 hidden md:table-cell text-gray-600">
                                {{ $personnel->fonction->nom_fonction ?? '-' }}

                            </td>

                            <td class="p-4 text-center">

                                <div class="flex justify-center gap-3">

                                    <a href="{{ route('personnels.show', $personnel->id_personnel) }}"
                                        class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">

                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('personnels.edit', $personnel->id_personnel) }}"
                                        class="px-3 py-1 bg-green-100 text-green-600 rounded-lg hover:bg-green-200">

                                        <i class="fas fa-edit"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center p-12 text-gray-400">

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

        <!-- PAGINATION -->
        <div class="p-4">
            {{ $personnels->withQueryString()->links() }}
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.querySelector("input[name='search']");
            const serviceSelect = document.querySelector("select[name='service']");
            const tableBody = document.querySelector("table tbody");
            const pagination = document.querySelector(".pagination");

            if (!searchInput || !serviceSelect) return;

            let timeout = null;

            function fetchData(page = 1) {

                const search = searchInput.value;
                const service = serviceSelect.value;

                fetch(`{{ route('personnels.index') }}?search=${search}&service=${service}&page=${page}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.text())
                    .then(html => {

                        const doc = new DOMParser().parseFromString(html, "text/html");

                        const tbody = doc.querySelector("tbody");
                        const newPagination = doc.querySelector(".pagination");

                        if (tbody) tableBody.innerHTML = tbody.innerHTML;

                        if (newPagination && pagination) {
                            pagination.innerHTML = newPagination.innerHTML;
                        }

                        attachPaginationEvent();

                    });

            }

            function attachPaginationEvent() {

                document.querySelectorAll(".pagination a").forEach(link => {

                    link.addEventListener("click", function(e) {

                        e.preventDefault();

                        const url = new URL(this.href);
                        const page = url.searchParams.get("page");

                        fetchData(page);

                    });

                });

            }

            /* SEARCH INPUT */
            searchInput.addEventListener("input", function() {

                clearTimeout(timeout);
                timeout = setTimeout(() => fetchData(), 400);

            });

            /* COMBO SERVICE */
            serviceSelect.addEventListener("change", function() {
                fetchData();
            });

            attachPaginationEvent();

        });
    </script>

@endsection
