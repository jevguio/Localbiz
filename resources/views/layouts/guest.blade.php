<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Localbiz') }}</title>
    <link rel="icon" type="image/png" href=" {{ asset('assets/img/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="toast toast-top toast-end">
        @if (session('success'))
            <div class="alert alert-success text-white" id="success-toast">
                <i class='bx bx-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error text-white" id="error-toast">
                <i class='bx bx-x-circle'></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    {{ $slot }}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-toast').fadeOut();
                $('#error-toast').fadeOut();
            }, 3000);
        });
    </script>
</body>

</html>
