<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(){
        $posts = Post::where('status', 'published')->orderBy('created_at', 'desc')->get();
        // echo $posts->title;
        return view('post', compact('posts'));
    }
}
