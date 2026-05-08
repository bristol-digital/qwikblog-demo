<!DOCTYPE html>
{{--
  Default starter layout. The package ships this as a sensible baseline —
  most host sites will have their own app.blade.php and only need to merge
  in the @stack('head') line and the RSS autodiscovery <link>. Don't take
  this file as canonical chrome.
--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Per-view <head> injection point. Show pages push Open Graph,
         Twitter Card and canonical link tags here. Required for SEO. --}}
    @stack('head')

    {{-- RSS feed autodiscovery. --}}
    <link rel="alternate" type="application/rss+xml"
          title="{{ config('app.name') }} RSS Feed"
          href="{{ url('/blog/feed.xml') }}">
</head>
<body class="bg-gray-50">

<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="/" class="text-xl font-bold text-gray-800">{{ config('app.name') }}</a>
            <div class="space-x-6">
                <a href="/" class="text-gray-600 hover:text-gray-900">Home</a>
                <a href="/blog" class="text-gray-600 hover:text-gray-900">Blog</a>
            </div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="mt-16 py-8 bg-gray-800 text-white">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
    </div>
</footer>

</body>
</html>
