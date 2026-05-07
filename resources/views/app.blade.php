<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Blog')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
{{-- Navigation --}}
<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="/" class="text-xl font-bold text-gray-800">My Site</a>
            <div class="space-x-6">
                <a href="/" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="/blog" class="text-gray-600 hover:text-gray-900">Blog</a>
                <a href="/admin" class="text-gray-400 hover:text-gray-700 text-sm">Admin</a>
            </div>
        </div>
    </div>
</nav>

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="mt-16 py-8 bg-gray-800 text-white">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; {{ date('Y') }} My Blog. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
