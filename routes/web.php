<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [HomeController::class, 'index']);

Route::get('/post', [PostController::class, 'index']);

Route::get('/about', function () {
    return view('about');
});

Route::get('/user/{id}', function ($id) {
    return "User: ".$id;
});