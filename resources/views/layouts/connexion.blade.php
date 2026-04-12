<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GesPerm | @yield('title', 'Bienvenue')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon PNG 32x32 -->
    <link rel="shortcut icon" href="{{ asset('images/CIT.ico') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}"> --}}


</head>

<body class="bg-gray-50 font-sans text-gray-800 overflow-hidden">

    <main class="h-screen flex flex-col">

        <div class="flex-grow flex justify-center items-center px-4">

            @yield('content')

        </div>

    </main>
    {{-- <script type="module" src="{{ asset('build/assets/app.js') }}"></script> --}}
</body>

</html>
