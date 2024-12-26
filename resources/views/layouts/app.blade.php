<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
          @if(Session::has('message'))
          var type= "{{ Session::get('alert-type', 'info') }}"
          switch (type) {
            case 'info':
              toastr.info("{{ Session::get('message') }}")
              break;
            case 'success':
              toastr.success("{{ Session::get('message') }}")
              break;
            case 'warning':
              toastr.options = {
                "closeButton": true,
                "timeOut": 0,
                "extendedTimeOut": 0,
                "tapToDismiss": false,
              };
              toastr.warning("{{ Session::get('message') }}")
              break;
            case 'error':
              toastr.options = {
                "closeButton": true,
                "timeOut": 0,
                "extendedTimeOut": 0,
                "tapToDismiss": false,
              };
              toastr.error("{{ Session::get('message') }}")
              break;
          }
          @endif
        </script>
    </body>
</html>
