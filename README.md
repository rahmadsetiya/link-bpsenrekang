# Link BPS Enrekang

A web application built with **Laravel 12**, **Inertia.js**, **Vue 3**, and **Tailwind CSS**.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Vue 3, Inertia.js |
| Styling | Tailwind CSS |
| Build Tool | Vite 7 |
| Database | MySQL |
| Auth | Laravel Breeze |

---

## Requirements

Make sure you have the following installed before starting:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm** >= 9.x
- **MySQL** (via Laragon or any local server)

---

## Installation & Setup

### 1. Clone the repository

```bash
git clone <repository-url> link-bpsenrekang
cd link-bpsenrekang
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Set up environment file

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure the database

Open `.env` and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=link_bpsenrekang
DB_USERNAME=root
DB_PASSWORD=root
```

### 5. Run database migrations

```bash
php artisan migrate
```

### 6. Install Node.js dependencies

```bash
npm install --legacy-peer-deps
```

> `--legacy-peer-deps` is required because `@vitejs/plugin-vue` has not yet declared official support for Vite 7.

---

## Running the App

You need **two servers running at the same time** — one for Laravel (backend) and one for Vite (frontend assets).

### Option A — Run everything with one command (recommended)

```bash
composer dev
```

This starts Laravel, Vite, queue worker, and log watcher all at once using `concurrently`.

### Option B — Run manually in separate terminals

**Terminal 1 — Laravel server:**
```bash
php artisan serve
```

**Terminal 2 — Vite dev server:**
```bash
npm run dev
```

Then open your browser and go to:
```
http://127.0.0.1:8000
```

> Do **not** open `http://localhost:5173` — that is Vite's internal asset server, not the app.

---

## Building for Production

```bash
npm run build
```

This compiles and minifies all frontend assets into the `public/build/` directory.

---

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Laravel controllers
│   │   └── Middleware/
│   └── Models/                # Eloquent models
├── database/
│   └── migrations/            # Database migrations
├── resources/
│   ├── css/
│   │   └── app.css            # Global styles (Tailwind)
│   └── js/
│       ├── app.js             # Frontend entry point
│       ├── Pages/             # Vue page components (views)
│       ├── Layouts/           # Shared page layouts
│       └── Components/        # Reusable UI components
├── routes/
│   ├── web.php                # Web routes
│   └── auth.php               # Auth routes
└── vite.config.js             # Vite configuration
```

---

## Adding a New Page

**1. Create a Vue file** in `resources/js/Pages/`, e.g. `resources/js/Pages/About.vue`:

```vue
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="About" />
    <AuthenticatedLayout>
        <div class="p-6">About page content here.</div>
    </AuthenticatedLayout>
</template>
```

**2. Add a route** in `routes/web.php`:

```php
Route::get('/about', function () {
    return Inertia::render('About');
});
```

The string passed to `Inertia::render()` maps directly to the file path inside `resources/js/Pages/`.

---

## Common Commands

| Command | Description |
|---|---|
| `php artisan migrate` | Run database migrations |
| `php artisan migrate:fresh` | Drop all tables and re-run migrations |
| `php artisan make:model Foo -mcr` | Create model, migration, and controller |
| `php artisan route:list` | List all registered routes |
| `npm run dev` | Start Vite dev server with HMR |
| `npm run build` | Build frontend assets for production |
| `composer dev` | Start all dev servers at once |
