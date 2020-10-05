<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Entry;
use App\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\EntryResource;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return response()->json(TagResource::collection($tags));
    }

    public function show(Tag $tag)
    {
        $name = $tag->name;

        $entries = EntryResource::collection(Entry::whereHas('Tag', function ($query) use ($name){
            $query->where('name', $name);
        })->get());
        
        $posts = PostResource::collection(Post::whereHas('Tag', function ($query) use ($name){
            $query->where('name', $name);
        })->get());
        
        $itemsUnSorted = $posts;
        $itemsUnSorted = $itemsUnSorted->merge($entries);
        $items = $itemsUnSorted->sortByDesc('created_at');
        
        return response()->json(['data' => $items, 'tag' => new TagResource($tag)]);
    }
    
    public function follow(Tag $tag){    
        if(!auth()->user()->tag->contains($tag)){
            auth()->user()->tag()->attach($tag);
        }
    }  
    
    public function unfollow(Tag $tag){  
        $tag->user()->detach(auth()->user()->id);
    }
}
