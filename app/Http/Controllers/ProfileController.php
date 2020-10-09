<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\PostResource;
use App\Http\Resources\EntryResource;
use App\Http\Resources\TagResource;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    /**
     * Return the specified user profile.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = ProfileService::show($user);

        return response()->json($user);
    }
    
    /**
     * Return the specified user posts.
     *
     * @param User $user
     * @return mixed
     */
    public function posts(User $user)
    {
        $posts = ProfileService::posts($user);

        return PostResource::collection($posts);
    }
    
    /**
     * Return the specified user entries.
     *
     * @param User $user
     * @return mixed
     */
    public function entries(User $user)
    {
        $entries = ProfileService::entries($user);

        return EntryResource::collection($entries);
    }
    
    /**
     * Return watched tags for a specified user
     *
     * @param User $user
     * @return mixed
     */
    public function tags(User $user)
    {
        $tags = ProfileService::tags($user);
        return TagResource::collection($tags);
    }
}
