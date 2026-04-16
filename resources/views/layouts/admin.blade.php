<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GesPerm | @yield('title', 'Gestion Permission')</title>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}"> --}}

    <!-- Favicon PNG 32x32 -->
    <link rel="shortcut icon" href="{{ asset('images/CIT.ico') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('icones/icone.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}"> --}}

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body x-data="{ open: false, notif: false, profile: false, logoutModal: false }" class="bg-[#F4F6FB] font-sans overflow-x-hidden">

    <!-- ================= NAVBAR ================= -->

    <header class="fixed top-0 left-0 right-0 h-16 bg-[#4B0082] text-white shadow z-50">

        <div class="h-full px-4 flex items-center justify-between">

            <!-- LEFT -->
            <div class="flex items-center gap-3">

                <button @click="open=!open" class="md:hidden text-white text-xl hover:text-[#FFD700] transition">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="#" class="flex items-center gap-3">

                    <div class="w-12 h-12 bg-white rounded-lg flex justify-center items-center shadow overflow-hidden">
                        <img src="{{ asset('images/CIT.png') }}" class="w-12 h-12 object-contain">
                    </div>

                    <div class="hidden sm:block">
                        <div class="text-[#FFD700] font-bold text-sm">
                            GesPerm
                        </div>
                        <div class="text-xs opacity-70">
                            Système sécurisé
                        </div>
                    </div>

                </a>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                @if (auth()->user()->type == 'admin')
                    <!-- NOTIFICATION -->

                    <div x-data="{ notif: false }" class="relative">
                        <!-- Bouton notification -->
                        <button @click="notif=!notif" class="relative hover:text-[#FFD700] transition duration-300">

                            <i class="fas fa-bell text-lg"></i>

                            @if ($expiredPermissions->isNotEmpty())
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs min-w-[18px] h-[18px] px-1 flex justify-center items-center rounded-full animate-pulse shadow">
                                    {{ $expiredPermissions->count() }}
                                </span>
                            @endif

                        </button>

                        <!-- Dropdown notifications -->
                        <div x-show="notif" x-cloak @click.away="notif=false" x-transition
                            class="absolute right-0 mt-3 w-80 sm:w-96 bg-white text-black rounded-xl shadow-xl border overflow-hidden z-50">

                            <div class="px-4 py-3 border-b bg-gray-50 flex justify-between">
                                <span class="text-sm font-semibold text-[#4B0082]">Notifications</span>
                                <span class="text-xs text-gray-400">{{ $expiredPermissions->count() }} expirées</span>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @forelse($expiredPermissions as $perm)
                                    <a href="{{ route('permissions.detail', $perm->id_permission) }}"
                                        class="flex gap-3 px-4 py-3 hover:bg-[#4B0082]/5 transition">
                                        <div
                                            class="w-9 h-9 bg-red-100 text-red-600 rounded-full flex justify-center items-center">
                                            <i class="fas fa-exclamation-triangle text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-black">
                                                Permission <strong>{{ ucfirst($perm->type_permission) }}</strong> de
                                                {{ $perm->nom }} {{ $perm->prenom }} expirée le
                                                {{ \Carbon\Carbon::parse($perm->date_fin)->format('d/m/Y') }}
                                            </p>
                                            <span class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($perm->date_fin)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <p class="px-4 py-3 text-sm text-gray-400">Aucune permission expirée</p>
                                @endforelse
                            </div>

                            <div class="border-t bg-gray-50 text-center p-2">
                                <a href="{{ route('permissions.encours') }}"
                                    class="text-sm text-[#4B0082] hover:text-[#FFD700] transition">
                                    Voir toutes
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- PROFILE -->
                <div class="relative">

                    <button @click="profile=!profile"
                        class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-xl hover:bg-white/20 transition">

                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-[#006233] via-[#FFD700] to-[#4B0082]
flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->login ?? '', 0, 1)) }}
                        </div>

                        <span class="hidden md:block text-sm">{{ auth()->user()->login ?? '' }}</span>

                        <i class="fas fa-chevron-down text-xs"></i>

                    </button>

                    <!-- PROFILE DROPDOWN -->
                    <div x-show="profile" x-cloak @click.away="profile=false" x-transition
                        class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border py-2 z-50 text-gray-700">

                        <a href="{{ route('parametre.utilisateurs.show', auth()->user()->id) }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                            <i class="fas fa-user text-gray-500"></i>
                            <span>Mon profil</span>
                        </a>

                        <a href="{{ route('parametre.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                            <i class="fas fa-cog text-gray-500"></i>
                            <span>Paramètres</span>
                        </a>

                        <div class="border-t my-2"></div>

                        <button @click="logoutModal=true"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition">

                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </header>

    <!-- OVERLAY MOBILE -->
    <div x-show="open" @click="open=false" class="fixed inset-0 bg-black/40 z-40 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside
        class="fixed top-16 left-0 bottom-0 w-64 bg-white border-r shadow-xl z-40
transform transition duration-300 overflow-y-auto md:translate-x-0"
        :class="open ? 'translate-x-0' : '-translate-x-full'">

        <div class="p-5 flex flex-col h-full">

            <p class="text-xs uppercase text-gray-400 mb-4">
                Navigation
            </p>

            <div class="space-y-2 flex-1">

                @php
                    $menus = [
                        [
                            'icon' => 'fa-chart-line',
                            'label' => 'Dashboard',
                            'route' => route('dashboard'),
                            'active' => 'dashboard*',
                            'role' => ['admin', 'manager'],
                        ],

                        [
                            'icon' => 'fa-person-military-rifle',
                            'label' => 'Militaires',
                            'route' => route('personnels.index'),
                            'active' => 'personnels.*',
                            'role' => ['admin', 'manager', 'SGCS', 'SGS', 'SGMI'],
                        ],

                        [
                            'icon' => 'fa-user-graduate',
                            'label' => 'Stagiaires',
                            'route' => route('stagiaires.index'),
                            'active' => 'stagiaires.*',
                            'role' => ['admin', 'manager', 'SGS', 'SGMI'],
                        ],

                        [
                            'icon' => 'fa-user-clock',
                            'label' => 'Permissions',
                            'route' => route('permissions.index'),
                            'active' => 'permissions.*',
                            'role' => ['admin', 'manager', 'SGCS', 'SGS', 'SGMI'],
                        ],

                        [
                            'icon' => 'fa-user-clock',
                            'label' => 'Permissions en attente',
                            'route' => route('permissions.enattente'),
                            'active' => 'permissions.*',
                            'role' => ['CCIT', 'CGS', 'CGCS', 'CGMI', 'CSTAGE', 'DFORMATION'],
                        ],

                        [
                            'icon' => 'fa-history ',
                            'label' => 'Historique Permissions',
                            'route' => route('avis.index'),
                            'active' => 'avis.*',
                            'role' => ['CCIT', 'CGS', 'CGCS', 'CGMI', 'CSTAGE', 'DFORMATION'],
                        ],

                        [
                            'icon' => 'fa-cog',
                            'label' => 'Paramètres',
                            'route' => route('parametre.index'),
                            'active' => 'parametre.*',
                            'role' => ['admin'],
                        ],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    @if (in_array(auth()->user()->type ?? 'user', $menu['role']))
                        <a href="{{ $menu['route'] }}" @click="open=false"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition

           {{ request()->routeIs($menu['active'])
               ? 'bg-[#4B0082] text-white shadow'
               : 'text-gray-700 hover:bg-[#FFD700]/30 hover:text-[#4B0082]' }}">

                            <i class="fas {{ $menu['icon'] }} w-5 text-center"></i>
                            <span>{{ $menu['label'] }}</span>

                            @if (request()->routeIs($menu['active']))
                                <span class="ml-auto w-2 h-2 bg-[#FFD700] rounded-full"></span>
                            @endif

                        </a>
                    @endif
                @endforeach

            </div>
            <!-- FOOTER COPYRIGHT SIDEBAR -->
            <div class="pt-4 border-t text-xs text-gray-400 text-center mt-4">

                <div class="bg-[#4B0082]/5 p-3 rounded-xl">

                    <div class="font-semibold text-[#4B0082]">
                        Gestion Permission
                    </div>

                    <div>
                        © {{ date('Y') }} Tous droits réservés
                    </div>

                    <div class="mt-1">
                        Version 1.0
                    </div>

                </div>

            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="pt-20 md:ml-64 p-5 min-h-screen">

        <div class="max-w-7xl mx-auto">
            {{-- Messages de succès ou d'erreur stylés --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="flex items-center justify-between gap-3 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-md mb-4 transition-all duration-500"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-3"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-3">

                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium text-green-800">{{ session('success') }}</span>
                    </div>

                    <button @click="show = false" class="text-green-800 hover:text-green-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            @endif

            @if (session('error'))
                {{-- Erreur --}}
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="flex items-center justify-between gap-3 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-md mb-4 transition-all duration-500"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-3"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-3">

                    <div class="flex items-center gap-3">
                        <!-- Icône d'erreur corrigée -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M12 3.5a9 9 0 100 18 9 9 0 000-18z" /> <!-- point d'exclamation -->
                        </svg>
                        <span class="font-medium text-red-800">{{ session('error') }}</span>
                    </div>

                    <!-- Croix de fermeture -->
                    <button @click="show = false" class="text-red-800 hover:text-red-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                </div>
            @endif
            @yield('content')
        </div>

    </main>

    <!-- LOGOUT MODAL -->
    <div x-show="logoutModal" x-cloak class="fixed inset-0 bg-black/50 flex justify-center items-center z-50"
        @click="logoutModal=false">

        <div @click.stop class="bg-white rounded-2xl shadow-xl w-80 p-6 text-center">

            <div class="text-red-500 text-4xl mb-4">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <h2 class="font-bold text-lg mb-2">
                Confirmer la déconnexion
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Voulez-vous vraiment quitter votre session ?
            </p>

            <div class="flex gap-3 justify-center">

                <button @click="logoutModal=false" type="button"
                    class="px-4 py-2 bg-gray-200 rounded-xl text-sm hover:bg-gray-300 transition">
                    Annuler
                </button>

                <form method="POST" action="{{ route('logout') }}" x-data="{ loading: false }" @submit="loading=true">

                    @csrf

                    <button type="submit" :disabled="loading"
                        class="px-4 py-2 bg-[#4B0082] text-white rounded-xl text-sm hover:bg-[#3a0068] transition flex items-center justify-center gap-2">

                        <span x-show="!loading">Oui, Déconnexion</span>

                        <span x-show="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            Déconnexion...
                        </span>

                    </button>

                </form>

            </div>
        </div>

    </div>
    <script src="{{ asset('js/alpine.min.js') }}" defer></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- <script type="module" src="{{ asset('build/assets/app.js') }}"></script> --}}
</body>

</html>
