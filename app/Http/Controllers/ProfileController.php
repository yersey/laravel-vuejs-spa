<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\PostResource;
use App\Http\Resources\EntryResource;
use App\Http\Resources\TagResource;
use Carbon\Carbon;

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
        $user['when'] = (new Carbon($user->created_at))->locale('pl')->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE);
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
        $posty = $user->post()->paginate(15);
        return PostResource::collection($posty);
    }
    
    /**
     * Return the specified user entries.
     *
     * @param User $user
     * @return mixed
     */
    public function entries(User $user)
    {
        $entries = $user->entry()->paginate(15);
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
        $tags = $user->tag;
        return TagResource::collection($tags);
    }
}
