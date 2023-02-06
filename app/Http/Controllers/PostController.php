<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        //memperoleh data
        $posts = post::Latest()->Paginate(10);

        return view('posts.index', compact('posts'));
    }
}
