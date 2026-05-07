# QwikBlog

A simple, file-based blog for Laravel — headless front-end + lightweight admin
with a Livewire-powered image gallery. No database required. Posts live in
`resources/posts/` as Markdown files with YAML front matter.

## Front-end routes

| Route          | Description     |
| -------------- | --------------- |
| `/blog`        | Post index      |
| `/blog/{slug}` | Single post     |

Views live at `resources/views/blog/index.blade.php` and `show.blade.php`.
Both extend the host site's `app.blade.php` layout. They are intentionally
basic so the host site can restyle them.

## Admin

A small, session-authenticated admin lives at `/admin`.

Set credentials in `.env`:

```dotenv
ADMIN_USERNAME=your-username
ADMIN_PASSWORD=your-password
```

If either variable is empty, login is refused — there is no default password.

| Route                          | Description           |
| ------------------------------ | --------------------- |
| `/admin/login`                 | Login form            |
| `/admin` → `/admin/posts`      | Posts index           |
| `/admin/posts/create`          | New post              |
| `/admin/posts/{slug}/edit`     | Edit post             |
| `/admin/posts/{slug}/images`   | Manage images (Livewire) |

The admin uses session auth via the `AdminAuth` middleware (alias `admin`
registered in `bootstrap/app.php`). Sessions are file-based by default
(`SESSION_DRIVER=file`), so no database is required.

## Post fields

Posts are stored as `resources/posts/YYYY-MM-DD-slug.md`. Front matter:

```yaml
---
title: My First Post
subtitle: An optional subtitle
summary: Short excerpt — used on listings.
category: Announcements
hero_image: /images/blog/my-first-post/1.jpg
author: Jane Editor
date: 2024-11-15
---

Markdown body goes here.
```

The admin writes this format automatically.

## Image gallery

Each post has its own image folder at `public/images/blog/{slug}/`. Images
are numbered `1.jpg`, `2.jpg`, … and ordering is set by the numeric filename.

The Livewire gallery (`/admin/posts/{slug}/images`) lets you:

- Drag-and-drop upload (multi-select, JPG/PNG/GIF, max 20 MB each)
- Resize and re-orient automatically (max 1600×1200, 85% quality JPG; GIFs
  pass through untouched so animations are preserved)
- Reorder images by drag
- Delete individual images
- Copy a path to the clipboard for pasting into the post's `hero_image` field

Images are kept independent of the `hero_image` front-matter field — set
that field manually in the post form to choose which image is the hero.
The gallery's "first" image is just the first one numerically; it is **not**
automatically used as the hero.

When a post is renamed, its image folder moves with it. When a post is
deleted, its image folder is deleted too.

## Cache

Posts are cached for an hour. To clear manually:

```bash
php artisan blog:refresh
```

The admin clears this cache automatically on every create/update/delete.

## Dependencies

- `laravel/framework` ^12
- `livewire/livewire` ^3.7 (used by the admin image gallery)

CDN-loaded by the admin chrome only (no Vite build required for the admin):
- Tailwind (Play CDN)
- Alpine.js
- Sortable.js (gallery reordering)
