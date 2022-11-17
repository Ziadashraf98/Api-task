<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function pinned_posts()
    {
        $posts = Post::where('status' , 0)->get();
        // dd($posts);
        return response(['success'=>true , 'data'=>PostResource::collection($posts)]);
    }

    public function user_posts()
    {
        $posts = Post::with('tags')->where('user_id' , Auth::id())->get();
        // dd($posts);
        return response(['success'=>true , 'data'=>$posts]);
    }

    public function single_post($id)
    {
        $post = Post::with('tags')->where('user_id' , Auth::id())->find($id);
        // dd($post);

        if($post)
        {
            return response(['success'=>true , 'data'=>$post]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }

    public function add_post(Request $request)
    {
        $validation = Validator::make($request->all() , [
            'title'=>'required|max:255',
            'body'=>'required',
            'status'=>'required',
            'image'=>'required|image',
        ]);

        if($validation->fails())
        {
            return response($validation->errors());
        }

        DB::beginTransaction();

        $post = Post::create([
            'user_id'=>Auth::id(),
            'title'=>$request->title,
            'body'=>$request->body,
            'status'=>$request->status,
            'image'=>$request->image->getClientOriginalName(),
        ]);

        foreach((array)$request->tags as $tag)
        {
            PostTag::create([
                'post_id'=>$post->id,
                'tag_id'=>$tag,
            ]);
        }
        
        DB::commit();
        return response(['success'=>true , 'data'=>new PostResource($post)]);
        
    }

    public function update_post($id , Request $request)
    {
        $post = Post::where('user_id' , Auth::id())->find($id);
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
        
        if($request->image)
        {
            $post->update(['image'=>$request->image->getClientOriginalName()]);
        }

        $post->update([
            'title'=>$request->title,
            'body'=>$request->body,
        ]);

        if($request->tags)
        {
            $post->tags()->sync($request->tags);
        }
        
        return response(['success'=>true , 'data'=>new PostResource($post)]);
    }

    public function delete_post($id)
    {
        $post = Post::where('user_id' , Auth::id())->find($id);
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
        $post = Post::onlyTrashed()->where('user_id' , Auth::id())->find($id);
        // dd($post);

        if($post)
        {
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }

    public function restore_post($id)
    {
        $post = Post::onlyTrashed()->where('user_id' , Auth::id())->find($id);
        // dd($post);
        
        if($post)
        {
            $post->restore();
            return response(['success'=>true , 'data'=>new PostResource($post)]);
        }
        
        return response(['success'=>false , 'data'=>'Post Not Found']);
    }
}
