# Tutorial: Laravel CRUD & Validasi

Tutorial ini akan memandu Anda membuat fitur manajemen data **Post (Artikel)** sederhana menggunakan Laravel. Kita akan menerapkan validasi form, keamanan CSRF, dan Feedback message (Flash data).

## Prasyarat
- Project Laravel sudah terinstall.
- Database sudah dikonfigurasi di file `.env`.

---

## Langkah 1: Membuat Model & Migration

Kita membutuhkan tabel untuk menyimpan data. Kita akan membuat Model `Post` sekaligus file migrasinya.

1. Jalankan perintah terminal:
   ```bash
   php artisan make:model Post -m
   ```

2. Buka file migration di `database/migrations/xxxx_xx_xx_create_posts_table.php` dan edit method `up()`:
   ```php
   public function up()
   {
       Schema::create('posts', function (Blueprint $table) {
           $table->id();
           $table->string('title'); // Judul artikel
           $table->text('content'); // Isi artikel
           $table->timestamps();
       });
   }
   ```

3. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```

4. Buka file Model `app/Models/Post.php` dan tambahkan `$fillable` agar kolom bisa diisi (*Mass Assignment*):
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Database\Eloquent\Model;

   class Post extends Model
   {
       use HasFactory;

       // Izinkan kolom ini diisi secara massal
       protected $fillable = [
           'title',
           'content',
       ];
   }
   ```

---

## Langkah 2: Membuat Controller

Kita akan membuat Controller menggunakan opsi `--resource` agar Laravel otomatis menyiapkan method CRUD (*index, create, store, edit, update, destroy*).

1. Jalankan perintah:
   ```bash
   php artisan make:controller PostController --resource
   ```

2. Buka `app/Http/Controllers/PostController.php` dan isi dengan kode lengkap berikut:
   ```php
   <?php

   namespace App\Http\Controllers;

   use App\Models\Post;
   use Illuminate\Http\Request;

   class PostController extends Controller
   {
       // MENAMPILKAN DATA (READ)
       public function index()
       {
           // Ambil data terbaru, paginate 5 per halaman
           $posts = Post::latest()->paginate(5);
           return view('posts.index', compact('posts'));
       }

       // FORM TAMBAH DATA (CREATE)
       public function create()
       {
           return view('posts.create');
       }

       // PROSES SIMPAN DATA (STORE)
       public function store(Request $request)
       {
           // 1. Validasi
           $request->validate([
               'title'   => 'required|min:5',
               'content' => 'required|min:10'
           ]);

           // 2. Simpan ke Database (Mass Assignment)
           Post::create($request->all());

           // 3. Redirect dengan Flash Message
           return redirect()->route('posts.index')
                            ->with('success', 'Data berhasil disimpan!');
       }

       // FORM EDIT DATA (EDIT)
       public function edit(Post $post)
       {
           return view('posts.edit', compact('post'));
       }

       // PROSES UPDATE DATA (UPDATE)
       public function update(Request $request, Post $post)
       {
           // 1. Validasi
           $request->validate([
               'title'   => 'required|min:5',
               'content' => 'required|min:10'
           ]);

           // 2. Update Data
           $post->update($request->all());

           // 3. Redirect
           return redirect()->route('posts.index')
                            ->with('success', 'Data berhasil diperbarui!');
       }

       // HAPUS DATA (DESTROY)
       public function destroy(Post $post)
       {
           $post->delete();

           return redirect()->route('posts.index')
                            ->with('success', 'Data berhasil dihapus!');
       }
   }
   ```

---

## Langkah 3: Mendaftarkan Route

Buka file `routes/web.php` dan tambahkan route resource.

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::resource('posts', PostController::class);
```

> **Note:** Satu baris ini otomatis membuat route untuk index, create, store, edit, update, dan destroy.

---

## Langkah 4: Membuat Views (Blade)

Kita akan menggunakan **Tailwind CSS** (via CDN) agar tampilan rapi tanpa setup ribet.

### 4.1. Layout Utama
Buat file `resources/views/layout.blade.php`:

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dumbways Laravel CRUD</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto mt-10 p-5">
        @yield('content')
    </div>

</body>
</html>
```

### 4.2. Halaman Index (List Data)
Buat file `resources/views/posts/index.blade.php`:

```blade
@extends('layout')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Artikel</h1>
        <a href="{{ route('posts.create') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Artikel
        </a>
    </div>

    {{-- Flash Message --}}
    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Konten</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $post->title }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ Str::limit($post->content, 50) }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        <a class="text-blue-600 hover:text-blue-900 mr-3" href="{{ route('posts.edit', $post->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-4">
        {{ $posts->links() }} {{-- Pagination Links --}}
    </div>
</div>
@endsection
```

### 4.3. Halaman Create (Form Tambah)
Buat file `resources/views/posts/create.blade.php`:

```blade
@extends('layout')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">Tambah Artikel Baru</h2>

        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Judul
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" 
                    id="title" name="title" type="text" placeholder="Judul Artikel" value="{{ old('title') }}">
                
                {{-- Error Message --}}
                @error('title')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                    Konten
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror" 
                    id="content" name="content" rows="5" placeholder="Isi artikel...">{{ old('content') }}</textarea>
                
                {{-- Error Message --}}
                @error('content')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Simpan
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('posts.index') }}">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

### 4.4. Halaman Edit (Form Edit)
Buat file `resources/views/posts/edit.blade.php`:

```blade
@extends('layout')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-xl font-bold mb-4">Edit Artikel</h2>

        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Method Spoofing --}}
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Judul
                </label>
                {{-- Value: Gunakan input lama jika ada error, jika tidak gunakan data database --}}
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" 
                    id="title" name="title" type="text" value="{{ old('title', $post->title) }}">
                
                @error('title')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                    Konten
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror" 
                    id="content" name="content" rows="5">{{ old('content', $post->content) }}</textarea>
                
                @error('content')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Update
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800" href="{{ route('posts.index') }}">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

## Langkah 5: Uji Coba

1. Jalankan server:
   ```bash
   php artisan serve
   ```

2. Buka browser dan akses: `http://127.0.0.1:8000/posts`

3. Coba fitur berikut:
   - **Create:** Isi form. Coba kosongkan untuk melihat pesan error validasi.
   - **Read:** Lihat data muncul di tabel.
   - **Update:** Edit data yang sudah ada.
   - **Delete:** Hapus data.

**Selamat! Anda telah berhasil membuat aplikasi CRUD Laravel lengkap dengan validasi.**