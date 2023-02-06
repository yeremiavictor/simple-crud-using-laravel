<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

// Fungsi edit gambar
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    // Detail data
    public function index()
    {
        //memperoleh data
        $posts = post::Latest()->Paginate(10);

        return view('posts.index', compact('posts'));
    }

    // Insert Data
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

    //Edit Data
        public function edit(Post $post){
            return view('posts.edit', compact('post'));
        }
        Public function update(Request $request, Post $post){
            //validasi
            $this-> validate($request, [
                'gambar'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'nama'          => 'required|min:5',
                'keterangan'    => 'required|min:10'
            ]);

            //cek apakah gambar di upload
            if($request->hasFile('gambar')){
                //upload gambar baru
                $gambar = $request->file('gambar');
                $gambar ->storeAs('public/posts', $gambar->hashName());
                //hapus gambar lama
                Storage::delete('public/posts/'.$post->gambar);
                
                //memperbarui post
                $post->update([
                    'nama'=> $request->nama,
                    'gambar'=> $gambar->hashname(),
                    'keterangan' => $request->keterangan
                ]);
            } else {
                //update post saja
                $post->update([
                    'nama'=> $request->nama,
                    'keterangan' => $request->keterangan
                ]);
            }

            // Redirect halaman
            return redirect()->route('posts.index')->with(['success' => "Tersimpan"]);
        }

    //delete
    public function destroy(Post $post)
    {
        //hapus gambar
        Storage::delete('public/posts/'.$post->image);

        //hapus post
        $post->delete();

        //Redirect halaman
        return redirect()->route('posts.index')->with(['success'=> 'Penghapusan berhasil']);
    }

}
