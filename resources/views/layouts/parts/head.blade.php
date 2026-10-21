<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS + Icons via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="/favicon.ico" rel="icon">

    <!-- Loading Scripts and Styles with Vite in Main Project

        Es gibt dafür keinen offiziellen Standard dafür JS-Code 
        über composer.json des Packages in Vite einzubinden.

        npm install
      
        # Run the Vite development server...
        npm run dev

        # Build and version the assets for production...
        npm run build
    -->
    {{-- @vite(['resources/css/app.scss', 'resources/js/app.js']) --}}

    {{-- Livewire-Styles (Pendant zu @livewireScripts in layouts/parts/script.blade.php) --}}
    @livewireStyles

</head>
