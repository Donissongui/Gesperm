@extends('layouts.admin')

@section('title', 'Profil utilisateur')

@section('content')

    <!-- ================= HEADER ================= -->

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-bold text-[#4B0082] flex items-center gap-2">
            <i class="fas fa-user"></i>
            Profil utilisateur
        </h2>

        <div class="flex gap-2">

            <button
                onclick="openEditModal('{{ $user->id }}','{{ $user->name }}','{{ $user->login }}','{{ $user->email }}')"
                class="bg-[#4B0082] text-white px-4 py-2 rounded-xl flex items-center gap-2 hover:bg-[#3a0066] transition">
                <i class="fas fa-user-edit"></i>
                Modification
            </button>

        </div>

    </div>

    <hr class="my-6 border-[#4B0082]">

    <!-- ================= CARD ================= -->

    <div class="flex justify-center mt-6">
        <div class="bg-white rounded-2xl shadow border p-6 max-w-2xl w-full">

            <div class="flex items-center gap-4 mb-6">

                <div
                    class="w-14 h-14 rounded-full
        bg-gradient-to-br from-[#006233] via-[#FFD700] to-[#4B0082]
        flex items-center justify-center text-white font-bold text-lg">

                    {{ strtoupper(substr($user->login ?? '', 0, 1)) }}

                </div>

                <div>
                    <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="text-gray-500 text-sm">Login</p>
                    <p class="font-medium">{{ $user->login }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Type</p>
                    <p class="font-medium capitalize">{{ $user->type }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Date création</p>
                    <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Dernière modification</p>
                    <p class="font-medium">{{ $user->updated_at->format('d/m/Y') }}</p>
                </div>

            </div>

        </div>
    </div>

    <!-- ================================================= -->
    <!-- ================= EDIT MODAL ==================== -->
    <!-- ================================================= -->



    <div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50"
        onclick="if(event.target.id=='editModal'){closeEditModal()}">

        <div class="bg-white w-full max-w-lg p-6 rounded-2xl">

            <h3 class="text-xl font-semibold mb-4">
                Modifier {{ $user->name }}
            </h3>

            <form id="editForm" method="POST">

                @csrf
                @method('PUT')

                <!-- Nom -->
                <input id="edit_name" name="name"
                    class="w-full border rounded-xl p-2 mb-3 focus:ring-2 focus:ring-[#4B0082] outline-none"
                    placeholder="Nom">

                <!-- Login -->
                <input id="edit_login" name="login"
                    class="w-full border rounded-xl p-2 mb-3 focus:ring-2 focus:ring-[#4B0082] outline-none"
                    placeholder="Login">

                <!-- Email -->
                <input id="edit_email" name="email" type="email"
                    class="w-full border rounded-xl p-2 mb-3 focus:ring-2 focus:ring-[#4B0082] outline-none"
                    placeholder="Email">

                <!-- Password -->
                <input name="password" type="password"
                    class="w-full border rounded-xl p-2 mb-1 focus:ring-2 focus:ring-[#4B0082] outline-none"
                    placeholder="Mot de passe (laisser vide)">
                <p class="text-gray-400 text-sm mb-3">
                    Laisser vide si vous ne voulez pas changer le mot de passe
                </p>

                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-200 px-4 py-2 rounded-xl hover:bg-gray-300 transition">
                        Annuler
                    </button>

                    <button class="bg-[#4B0082] text-white px-4 py-2 rounded-xl hover:bg-[#3a0066] transition">
                        Modifier
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- ================= JS ================= -->

    <script>
        function openEditModal(id, name, login, email) {

            document.getElementById('editForm').action =
                "{{ url('/parametres/utilisateurs') }}/" + id;

            document.getElementById('edit_name').value = name || "";
            document.getElementById('edit_login').value = login || "";
            document.getElementById('edit_email').value = email || "";

            document.getElementById('editModal')
                .classList.replace('hidden', 'flex');

        }

        function closeEditModal() {

            document.getElementById('editModal')
                .classList.replace('flex', 'hidden');

        }
    </script>

@endsection
