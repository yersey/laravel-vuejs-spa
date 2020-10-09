<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Http\Resources\TagResource;
use App\Services\TagService;

class TagController extends Controller
{
    /**
     * Return a list of tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tags = TagService::index();

        return response()->json(TagResource::collection($tags));
    }

    /**
     * Return a list of resources having specified tag.
     *
     * @param Tag $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tag $tag)
    {
        $items = TagService::show($tag);
        
        return response()->json(['data' => $items, 'tag' => new TagResource($tag)]);
    }
    
    /**
     * Follow specified tag.
     *
     * @param Tag $tag
     * @return void
     */
    public function follow(Tag $tag){    
        TagService::follow($tag);
    }  
    
    /**
     * Unfollow specified tag.
     *
     * @param Tag $tag
     * @return void
     */
    public function unfollow(Tag $tag){  
        TagService::unFollow($tag);
    }
}
