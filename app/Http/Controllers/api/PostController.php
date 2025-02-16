<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\json;
use function Pest\Laravel\post;

class PostController extends Controller
{
    // get data post 10 per tabs
    public function index(){
        $post = Post::paginate(10);                               
        if($post->count() < 1){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Post kosong',
                'page' => $post->currentPage(),                
                'data' => [],
                'total-data' => 0
            ]);
        }
        return response()->json([
            'status' => 'success',
            'page' => $post->currentPage(),
            'data' => $post->items(),
            'total-data' => $post->total(),            
        ],200);
    }
    // Mengambil data post satuan
    public function show($id){
        $post = Post::find($id);
        if(!$post){
            return response()->json([
                'status' => 'error',
                'message' => 'Data post yang anda cari tidak ada'                
            ],404);            
        }
        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }
    // Menambahkan data post
    public function store(Request $request){        
        // untuk validasi
        $rules = [
            'title' => 'required|min:5|max:150',
            'description' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:5120'
        ];
        // error feedback 
        $error_message = [
            'title.required' => 'judul harus diisi',
            'title.min' => 'judul Minimal 5 karakter',
            'title.max' => 'judul tidak boleh lebih dari 150 karakter',
            'description.required' => 'Deskripsi harus diisi',
            'image.mimes' => 'Gambar harus berupa png,jpg atau jpeg',
            'image.max' => 'Ukuran file gambar tidak boleh lebih dari mb'
        ];
        $validator = Validator::make($request->all(),$rules,$error_message);        
        // cek apakah validasi berhasil atau tidak
        // dd($validator);
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Menambahkan Data',
                'error' => $validator->errors()
            ],400);            
        }
        // menampung data untuk di buat
        $store = [
            'title' => $request->title,
            'description' => $request->description,            
        ];
        // jika ada gamabr maka simpan gambar
        if($request->file('image')){
            $file = $request->file('image');
            $file_name = $file->hashName();
            Storage::disk('public')->putFileAs('images',$file,$file_name);
            $store['image'] = $file_name;
        }
        // menambahkan data ke database
        $created = Post::create($store);
        if($created){
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data Post'
            ],201);
        }else{
            return response()->json([
                'error' => 'error',
                'message' => 'Ada sedikit masalah teknis'
            ]);
        }
    }
}
