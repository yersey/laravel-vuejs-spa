<?php

namespace App\Services;

use App\Tag;
use App\Entry;
use App\Post;
use App\Http\Resources\EntryResource;
use App\Http\Resources\PostResource;

class TagService
{
    /**
     * Return a list of tags.
     * @return mixed
     */
    public static function index()
    {
        return Tag::all();
    }

    /**
     * Return a list of resources having specified tag.
     *
     * @param Tag $tag
     * @return mixed
     */
    public static function show(Tag $tag)
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

        return $items;
    }

    /**
     * Follow specified tag
     *
     * @param Tag $tag
     * @return void
     */
    public static function follow(Tag $tag)
    {
        if(!auth()->user()->tag->contains($tag)){
            auth()->user()->tag()->attach($tag);
        }
    }

    /**
     * Unfollow specified tag
     *
     * @param Tag $tag
     * @return void
     */
    public static function unFollow(Tag $tag)
    {
        $tag->user()->detach(auth()->user()->id);
    }
}