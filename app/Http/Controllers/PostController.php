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

    public function create(){
        return view('posts.create');
    }

    
    public function store(Request $request){
        //validasi
        $this-> validate($request, [
            'gambar'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama'          => 'required|min:5',
            'keterangan'    => 'required|min:10'
        ]);

        //upload gambar
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/posts', $gambar->hashName());

        //Insert to DB
        Post::create([
            'nama'=> $request->nama,
            'gambar'=> $gambar->hashname(),
            'keterangan' => $request->keterangan
        ]);

        // Redirect halaman
        return redirect()->route('posts.index')->with(['success' => "Tersimpan"]);

    }
}
