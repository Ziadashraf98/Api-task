<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function user_posts()
    {
        $posts = Post::where('user_id' , Auth::user()->id)->get();
        // dd($posts);
        return response(['success'=>true , 'data'=>PostResource::collection($posts)]);
    }

    public function single_post($id)
    {
        $post = Post::where('user_id' , Auth::user()->id)->find($id);
        // dd($post);

        if($post)
        {
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }

    public function add_post(Request $request)
    {
        $validation = Validator::make($request->all() , [
            'title'=>'required|max:255',
            'body'=>'required',
            'image'=>'required|image',
        ]);

        if($validation->fails())
        {
            return response($validation->errors());
        }

        $post = Post::create([
            'user_id'=>Auth::user()->id,
            'title'=>$request->title,
            'body'=>$request->body,
            'image'=>$request->image->getClientOriginalName(),
        ]);
        
        return response(['success'=>true , 'data'=>new PostResource($post)]);
    }

    public function update_post($id , Request $request)
    {
        $post = Post::where('user_id' , Auth::user()->id)->find($id);
        // dd($post);
        
        $validation = Validator::make($request->all() , [
            'title'=>'required|max:255',
            'body'=>'required',
            'image'=>'image',
        ]);
    
        if($validation->fails())
        {
            return response($validation->errors());
        }

        $post->update([
            'title'=>$request->title,
            'body'=>$request->body,
        ]);
        
        return response(['success'=>true , 'data'=>new PostResource($post)]);
    }

    public function delete_post($id)
    {
        $post = Post::where('user_id' , Auth::user()->id)->find($id);
        // dd($post);
        
        if($post)
        {
            $post->delete();
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }

        return response(['success'=>false , 'data'=>'Post Not Found']);
    }

    public function show_delete_posts($id)
    {
        $post = Post::onlyTrashed()->where('user_id' , Auth::user()->id)->find($id);
        // dd($post);

        if($post)
        {
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }

    public function restore_post($id)
    {
        $post = Post::onlyTrashed()->where('user_id' , Auth::user()->id)->find($id);
        // dd($post);
        
        if($post)
        {
            $post->restore();
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }
}
