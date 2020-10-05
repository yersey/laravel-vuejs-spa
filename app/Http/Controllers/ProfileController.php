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
    public function show(User $user)
    {
        $user['when'] = (new Carbon($user->created_at))->locale('pl')->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE);
        return response()->json($user);
    }
    
    public function posts(User $user)
    {
        $posty = $user->post()->paginate(15);
        return PostResource::collection($posty);
    }
    
    public function entries(User $user)
    {
        $entries = $user->entry()->paginate(15);
        return EntryResource::collection($entries);
    }
    
    public function tags(User $user)
    {
        $tags = $user->tag;
        return TagResource::collection($tags);
    }
}
