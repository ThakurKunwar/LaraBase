<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraBase</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-8">
        @yield('content')
    </div>

    @livewireScripts
    @stack('scripts')
</body>