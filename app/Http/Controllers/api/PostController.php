<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

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
}
