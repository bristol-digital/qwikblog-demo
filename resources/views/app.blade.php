<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Site')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-4">
        <a href="/" class="text-xl font-bold">My Site</a>
        <a href="/blog" class="ml-4 text-gray-600 hover:text-gray-900">Blog</a>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="mt-16 py-8 bg-gray-100">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; {{ date('Y') }} My Site</p>
    </div>
</footer>
</body>
</html>
