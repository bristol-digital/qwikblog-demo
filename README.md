# QwikBlog

A simple, file-based blog system for Laravel with Tailwind CSS.

## Installation
```bash
composer require yourname/qwikblog
```

## Requirements

- Laravel 10+ or 11+
- Tailwind CSS (for default styling)

## Setup

1. Publish the config:
```bash
php artisan vendor:publish --tag=qwikblog-config
```

2. Create posts directory:
```bash
mkdir resources/posts
```

3. Set your layout in `config/qwikblog.php`:
```php
'layout' => 'layouts.app', // Your main layout
```

4. Make sure your `tailwind.config.js` includes the package views:
```js
content: [
    './resources/**/*.blade.php',
    './vendor/yourname/qwikblog/src/resources/**/*.blade.php',
],
plugins: [
    require('@tailwindcss/typography'),
],
```

5. Install Tailwind Typography plugin:
```bash
npm install @tailwindcss/typography
```

## Usage

The blog automatically uses your app's layout. Just create posts:

**resources/posts/2024-11-20-my-first-post.md:**
```markdown
---
title: My First Blog Post
subtitle: An introduction to our new blog
category: Announcements
hero_image: /images/hero-1.jpg
---

Your content here in **Markdown**.
```

Visit `/blog` to see all posts.

## Customization

### Use Your Own Layout

Edit `config/qwikblog.php`:
```php
'layout' => 'layouts.my-custom-layout',
```

### Customize Views

Publish and edit the views:
```bash
php artisan vendor:publish --tag=qwikblog-views
```

Views will be in `resources/views/vendor/qwikblog/`.

### Change Route Prefix
```php
'route' => [
    'prefix' => 'articles', // Now /articles instead of /blog
    'name_prefix' => 'articles.',
],
```

### Use as Partials (No Layout)

If you want to include blog views in your own pages without a layout:
```php
'layout' => null,
```

Then in your own views:
```blade
@extends('my-layout')

@section('content')
    @include('qwikblog::blog.index')
@endsection
```

## Tailwind Classes

The package uses standard Tailwind classes. To customize colors:

- Primary color: Change `blue-600` to your brand color
- Just search and replace in published views

## License

MIT
