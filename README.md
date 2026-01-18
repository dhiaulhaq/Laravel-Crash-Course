# Laravel Crash Course - Dumbways.id

Selamat datang di repositori untuk materi **Laravel Crash Course** dari Dumbways.id.

Proyek ini adalah implementasi dari modul pembelajaran Laravel tingkat dasar hingga menengah. Repositori ini dirancang sebagai referensi kode (*source code reference*) bagi peserta untuk memahami bagaimana fitur-fitur Laravel dibangun secara bertahap.

## 📚 Cakupan Materi

Kode dalam repositori ini mencakup implementasi dari sesi-sesi berikut:

* **Pengenalan & Instalasi:** Struktur dasar folder Laravel, konfigurasi `.env`, dan artisan command.
* **Routing & Views:** Implementasi routing dasar, parameter, dan Blade Templating Engine.
* **Controller & Request:** Penggunaan Controller sebagai penghubung logika, dan passing data ke Views.
* **Database & Migration:** Skema database menggunakan Migration dan pengisian data dummy dengan Seeder.
* **Eloquent ORM:** Interaksi database menggunakan Model Eloquent (Create, Read, Filter).
* **CRUD Lengkap & Validasi:** Manajemen data (Create, Read, Update, Delete) dengan validasi input yang aman.
* **Authentication:** Sistem login dan register menggunakan Laravel Breeze.

---

## 🚀 Panduan Implementasi (Tutorial)

Untuk memudahkan praktik mandiri, kami telah menyediakan panduan langkah demi langkah (*step-by-step*) khusus untuk sesi yang kompleks. Silakan klik link di bawah untuk membuka tutorialnya:

### 📝 [Tutorial Sesi 6: CRUD & Validasi](./TUTORIAL_CRUD.md)
Pelajari cara membangun fitur manajemen data lengkap dari nol, termasuk:
* Membuat Form HTML yang aman (CSRF).
* Validasi input di sisi server.
* Teknik Method Spoofing (PUT/DELETE).
* Menampilkan pesan sukses (Flash Messages).

### 🔐 [Tutorial Sesi 7: Authentication (Breeze)](./TUTORIAL_BREEZE.md)
Panduan instalasi dan konfigurasi sistem autentikasi instan:
* Instalasi Laravel Breeze.
* Setup sistem Login & Register.
* Memproteksi halaman dengan Middleware.

---

## 🛠️ Cara Menjalankan Project

Jika Anda ingin mencoba menjalankan *source code* ini di komputer lokal Anda, ikuti langkah berikut:

### 1. Clone Repositori
```bash
git clone [https://github.com/username-anda/laravel-crash-course.git](https://github.com/username-anda/laravel-crash-course.git)
cd laravel-crash-course
```

### 2. Install Dependencies
Install library PHP dan aset frontend:
```bash
composer install
npm install && npm run build
```

### 3. Setup Environment
Salin file `.env.example` menjadi `.env` dan atur konfigurasi database Anda.
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi Database
Jalankan migrasi dan seeder untuk mengisi data awal:
```bash
php artisan migrate --seed
```

### 5. Jalankan Server
```bash
php artisan serve
```
Buka browser dan akses [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

Happy Coding! 🚀