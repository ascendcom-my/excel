<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('vendor/excel/css/app.css') }}">

        <!-- Scripts -->
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $title }}
                    </h2>
                    <div class="py-3">
                        <a href="{{ route('excel.export.getIndex') }}" class="w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Export</a>
                        <a href="{{ route('excel.import.getIndex') }}" class="mx-2 w-auto bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Import</a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white shadow-xl sm:rounded-lg">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
