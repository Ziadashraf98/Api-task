<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagsResource;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Translation\Catalogue\TargetOperation;

class TagController extends Controller
{
    public function get_tags()
    {
        $tags = Tag::all();
        return response(['success'=>true , 'data'=>TagsResource::collection($tags)]);
    }

    public function add_tag(Request $request)
    {
        $validation = Validator::make($request->all() , [
            'tag_name'=>'required|min:3|max:15|unique:tags',
        ]);
    
        if($validation->fails())
        {
            return response($validation->errors());
        }

        $tag = Tag::create(['tag_name'=>$request->tag_name]);
        
        return response(['success'=>true , 'data'=>new TagsResource($tag)]);
    }

    public function update_tag($id , Request $request)
    {
        // $tag = Tag::where('id' , $id)->first();
        $tag = Tag::find($id);
        
        $validation = Validator::make($request->all() , [
            'tag_name'=>'required|min:3|max:15|unique:tags',
        ]);
    
        if($validation->fails())
        {
            return response($validation->errors());
        }

        $tag->update(['tag_name'=>$request->tag_name]);
        
        return response(['success'=>true , 'data'=>new TagsResource($tag)]);
    }

    public function delete_tag($id)
    {
        $tag = Tag::find($id);
        
        if($tag)
        {
            $tag->delete();
            return response(['success'=>true , 'data'=>new TagsResource($tag)]);
        }

        return response(['success'=>false , 'data'=>'Post Not Found']);
    }
}
