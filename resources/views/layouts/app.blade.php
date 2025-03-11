<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href=" {{ asset('assets/img/LocalBIZ.png') }}">
   
    <title>{{ __('Localbiz') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0-beta.8/daisyui.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
     
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width:'100vw !important';
        } .flatpickr-calendar {
            padding:5%;
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
        }
        #dateRangePicker{ 
            visibility: hidden;
            width: 0;
            height:0;
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 250px;
            text-align: center;
        }
        .button-group {
            margin-top: 15px;
        } 
        #cancel-picker { 
            
            padding: 10px 20px;
            border: none; 
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            background: #dc3545;
        }
        #confirm-picker { 
            
            padding: 10px 20px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px; 
        }
        button:hover {
            opacity: 0.9;
        }
    </style> 
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body class="font-sans antialiased"  >
    <div class="    " style="width:100%;  ">
        @include('layouts.navigation')

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

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main style="width:'100%'">
            {{ $slot }}
        </main>
    </div>

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
