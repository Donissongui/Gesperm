@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')

    <!-- ================= BREADCRUMB ================= -->
    <nav class="mb-6" aria-label="breadcrumb">
        <ol class="flex gap-2 text-sm">

            <!-- Paramètres -->
            <li>
                <a href="{{ route('parametre.index') }}"
                    class="text-gray-500 hover:text-[#4B0082] flex items-center gap-1 transition font-medium">
                    <i class="fas fa-cogs"></i>
                    Paramètres
                </a>
            </li>

            <!-- Séparateur -->
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </li>

            <!-- Page active -->
            <li
                class="px-3 py-1 bg-[#4B0082]/10 text-[#4B0082] font-semibold
            flex items-center gap-2 rounded-lg">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </li>

        </ol>
    </nav>

    <!-- ================= HEADER ================= -->
    <div class="flex flex-wrap justify-between items-center gap-3">
        <h2 class="text-xl font-bold text-[#4B0082] flex items-center gap-2">
            Gestion utilisateurs
        </h2>

        <button onclick="openCreateModal()" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Ajouter utilisateur
        </button>
    </div>

    <hr class="my-6 border-[#4B0082]">

    <!-- ================= SEARCH ================= -->
    <form method="GET" action="{{ route('parametre.utilisateurs.index') }}" class="mt-6 mb-4">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher un utilisateur..."
                    class="w-full border rounded-xl pl-10 pr-4 py-2 focus:ring-2 focus:ring-[#4B0082] outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#4B0082] text-white px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-search"></i> Rechercher
                </button>

                <a href="{{ route('parametre.utilisateurs.index') }}"
                    class="bg-gray-200 text-gray-700 px-5 py-2 rounded-xl flex items-center gap-2">
                    <i class="fas fa-rotate-left"></i> Réinitialiser
                </a>
            </div>
        </div>
    </form>

    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-2xl shadow border overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#4B0082]/5 border-b">
                <tr>
                    <th class="p-4">Utilisateur</th>
                    <th class="p-4 hidden md:table-cell">Login</th>
                    <th class="p-4 hidden md:table-cell">Email</th>
                    <th class="p-4 hidden md:table-cell">Type</th>
                    <th class="p-4 hidden md:table-cell">Personnel</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="p-4 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br
                        from-[#006233] via-[#FFD700] to-[#4B0082]
                        flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->login ?? '', 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </td>

                        <td class="p-4 hidden md:table-cell">{{ $user->login }}</td>
                        <td class="p-4 hidden md:table-cell">{{ $user->email }}</td>
                        <td class="p-4 hidden md:table-cell capitalize">{{ $user->type }}</td>
                        <td class="p-4 hidden md:table-cell">
                            {{ $user->personnel->grade->libelle_grade }}
                            {{ $user->personnel ? $user->personnel->nom . ' ' . $user->personnel->prenom : '-' }}
                        </td>

                        <td class="p-4 text-center space-x-2">
                            <button
                                onclick="openEditModal('{{ $user->id }}','{{ $user->name }}','{{ $user->login }}','{{ $user->email }}','{{ $user->type }}','{{ $user->id_personnel }}')"
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button onclick="openDeleteModal('{{ $user->id }}')"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="p-4">
        {{ $users->links() }}
    </div>

    <!-- ================= CREATE MODAL ================= -->
    <div id="createModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">
            <h3 class="text-xl font-semibold mb-4">Ajouter utilisateur</h3>

            <form method="POST" action="{{ route('parametre.utilisateurs.store') }}">
                @csrf

                <input name="name" placeholder="Nom" class="w-full border rounded-xl p-2 mb-3 capitalize">
                <input name="login" placeholder="Login" class="w-full border rounded-xl p-2 mb-3 capitalize">
                <input name="email" type="email" placeholder="Email" class="w-full border rounded-xl p-2 mb-3">
                <input name="password" type="password" placeholder="Mot de passe" class="w-full border rounded-xl p-2 mb-3">

                <select name="type" class="w-full border rounded-xl p-2 mb-3">
                    <option value="admin">Administrateur</option>
                    <option value="manager">Manager</option>
                    <option value="CCIT">CCIT</option>
                    <option value="CGS">CGS</option>
                    <option value="CGCS">CGCS</option>
                    <option value="CGMI">CGMI</option>
                    <option value="CSTAGE">CSTAGE</option>
                    <option value="DFORMATION">DFORMATION</option>
                    <option value="SGCS">SGCS</option>
                    <option value="SGS">SGS</option>
                    <option value="SGMI">SGMI</option>
                </select>

                <!-- Personnel -->
                <select name="id_personnel" class="w-full border rounded-xl p-2 mb-4">
                    <option value="">-- Sélectionner un personnel --</option>
                    @foreach ($personnels as $personnel)
                        <option value="{{ $personnel->id_personnel }}">
                            {{ $personnel->nom }} {{ $personnel->prenom }} ({{ $personnel->matricule }})
                        </option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()"
                        class="bg-gray-200 px-4 py-2 rounded-xl">Annuler</button>
                    <button class="bg-[#4B0082] text-white px-4 py-2 rounded-xl">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT MODAL ================= -->
    <div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">
            <h3 class="text-xl font-semibold mb-4">Modifier utilisateur</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <input id="edit_name" name="name" class="w-full border rounded-xl p-2 mb-3">
                <input id="edit_login" name="login" class="w-full border rounded-xl p-2 mb-3">
                <input id="edit_email" name="email" class="w-full border rounded-xl p-2 mb-3">
                <input name="password" placeholder="Mot de passe (laisser vide)"
                    class="w-full border rounded-xl p-2 mb-3">

                <select id="edit_type" name="type" class="w-full border rounded-xl p-2 mb-3">
                    @php
                        $types = [
                            'admin' => 'Administrateur',
                            'manager' => 'Manager',
                            'CCIT' => 'CCIT',
                            'CGS' => 'CGS',
                            'CGCS' => 'CGCS',
                            'CGMI' => 'CGMI',
                            'CSTAGE' => 'CSTAGE',
                            'DFORMATION' => 'DFORMATION',
                            'SGCS' => 'SGCS',
                            'SGS' => 'SGS',
                            'SGMI' => 'SGMI',
                        ];
                    @endphp

                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" {{ $user->type == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <!-- Personnel -->
                <select id="edit_personnel" name="id_personnel" class="w-full border rounded-xl p-2 mb-4">
                    <option value="">-- Sélectionner un personnel --</option>
                    @foreach ($personnels as $personnel)
                        <option value="{{ $personnel->id_personnel }}">
                            {{ $personnel->grade->libelle_grade }}{{ $personnel->nom }} {{ $personnel->prenom }}
                        </option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-200 px-4 py-2 rounded-xl">Annuler</button>
                    <button class="bg-[#4B0082] text-white px-4 py-2 rounded-xl">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= DELETE MODAL ================= -->
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md p-6 rounded-2xl">
            <h3 class="text-red-600 text-lg font-semibold mb-4">Confirmer suppression</h3>
            <p class="mb-6 text-gray-600">Voulez-vous supprimer cet utilisateur ?</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="bg-gray-200 px-4 py-2 rounded-xl">Annuler</button>
                    <button class="bg-red-600 text-white px-4 py-2 rounded-xl">Supprimer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= JS ================= -->
    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.replace('hidden', 'flex');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.replace('flex', 'hidden');
        }

        function openEditModal(id, name, login, email, type, id_personnel) {
            document.getElementById('editForm').action = "{{ url('/parametres/utilisateurs') }}/" + id;
            document.getElementById('edit_name').value = name || "";
            document.getElementById('edit_login').value = login || "";
            document.getElementById('edit_email').value = email || "";
            document.getElementById('edit_type').value = type || "admin";
            document.getElementById('edit_personnel').value = id_personnel || "";

            document.getElementById('editModal').classList.replace('hidden', 'flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.replace('flex', 'hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = "{{ url('/parametres/utilisateurs') }}/" + id;
            document.getElementById('deleteModal').classList.replace('hidden', 'flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.replace('flex', 'hidden');
        }
    </script>

@endsection
