# Tutorial: Authentication dengan Laravel Breeze

Tutorial ini adalah panduan untuk mengimplementasikan sistem **Login**, **Register**, dan **Reset Password** secara instan menggunakan starter kit **Laravel Breeze**.

---

## Langkah 1: Instalasi Laravel Breeze

Pastikan Anda berada di root folder project Laravel. Buka terminal dan jalankan perintah berikut untuk download package Breeze:

```bash
composer require laravel/breeze --dev
```

## Langkah 2: Instalasi Scaffolding

Setelah package ter-download, kita perlu menjalankan perintah instalasi untuk men-generate file-file view, route, dan controller yang dibutuhkan untuk autentikasi.

Jalankan perintah install:

```bash
php artisan breeze:install
```

Terminal akan memberikan beberapa pertanyaan. Silahkan pilih opsi berikut:
1. **Which Breeze stack would you like to install?**
    - Pilih: Blade with Alpine (Ketik `blade` lalu Enter, atau pilih opsi `0`/`1` sesuai tampilan terminal).
2. **Would you like to support dark mode?**
    - Pilih: Yes atau No (bebas).
3. **Would you like to install a testing framework?**
    - Pilih default (PHPUnit atau Pest).

## Langkah 3: Migrasi Database & Compile Assets

Breeze akan menambahkan tabel baru (seperti users, password_reset_tokens) dan file CSS/JS baru.
1. Jalankan migrasi untuk membuat tabel users:
```bash
php artisan migrate
```
2. Install dependency Node.js dan compile assets (untuk Tailwind CSS):
```bash
npm install
npm run build
```

## Langkah 4: Uji Coba Login & Register

1. Jalankan server Laravel:
```bash
php artisan serve
```
2. Buka browser: `http://127.0.0.1:8000`.
3. Lihat di pojok kanan atas, sekarang sudah muncul link Log in dan Register.
4. Coba lakukan registrasi akun baru. Setelah berhasil, kita akan diarahkan ke halaman `/dashboard`.

## Langkah 5: Memproteksi Route (Middleware)

Sekarang kita akan melindungi fitur CRUD Post (dari Sesi 6) agar hanya user yang sudah login yang bisa mengaksesnya.

Buka file routes/web.php.

**Cara 1: Grouping Route**

Bungkus route resource `posts` ke dalam grup middleware `auth`.

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Halaman awal bisa diakses siapa saja
Route::get('/', function () {
    return view('welcome');
});

// Route yang butuh login
Route::middleware('auth')->group(function () {
    
    // Dashboard bawaan Breeze
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Posts (Hanya bisa diakses jika login)
    Route::resource('posts', PostController::class);
    
});

require __DIR__.'/auth.php';
```

**Cara 2: Proteksi Individual**

Jika hanya ingin memproteksi route tertentu, kita bisa menyambungkan method middleware() langsung di definisinya:

```php
Route::resource('posts', PostController::class)->middleware('auth');
```

## Langkah 6: Menyesuaikan Navbar

Secara default, Breeze menggunakan layout:
- `layouts/app.blade.php` (untuk user login).
- `layouts/guest.blade.php` (untuk tamu).

Jika ingin menggabungkan CRUD Post ke dalam layout dashboard Breeze:
1. Buka `resources/views/posts/index.blade.php`.
2. Ubah `@extends('layout')` (layout manual kita di Sesi 6) menjadi `@extends('layouts.app')` atau component syntax `<x-app-layout>`.
3. Sesuaikan slot konten. Breeze menggunakan `{{ $slot }}`, bukan `@yield('content')`.

Contoh penyesuaian di `resources/views/posts/index.blade.php`:

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Artikel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                ...
            </div>
        </div>
    </div>
</x-app-layout>
```

---
Selamat! Aplikasi Anda sekarang sudah memiliki sistem autentikasi yang aman.