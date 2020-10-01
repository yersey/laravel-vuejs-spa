<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Wpis;
use App\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\WpisResource;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['show', 'index']]);
    }

    public function index()
    {
        $tags = Tag::all();
        return response()->json(TagResource::collection($tags));
    }

    public function show($name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        
        $wpisy = WpisResource::collection(Wpis::whereHas('Tag', function ($query) use ($name){
            $query->where('name', $name);
        })->get());
        
        $posts = PostResource::collection(Post::whereHas('Tag', function ($query) use ($name){
            $query->where('name', $name);
        })->get());
        
        $itemsUnSorted = $posts;
        $itemsUnSorted = $itemsUnSorted->merge($wpisy);
        $items = $itemsUnSorted->sortByDesc('created_at');
        
        //return response()->json(['data' => $items, 'tag' => ['name' => $name, 'followers' => $tag->user()->count(), 'isFollow' => auth()->user() ? auth()->user()->tag->contains($tag) : false]]);
        return response()->json(['data' => $items, 'tag' => new TagResource($tag)]);
    }
    
    public function follow($name){    
        $tag = Tag::where('name', $name)->firstOrFail();
        if(!auth()->user()->tag->contains($tag))
            auth()->user()->tag()->attach($tag);  
    }  
    
    public function unfollow($name){  
        $tag = Tag::where('name', $name)->firstOrFail();
        $tag->user()->detach(auth()->user()->id);
    }
}
