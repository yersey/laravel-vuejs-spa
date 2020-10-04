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
    public function show($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        $user['when'] = (new Carbon($user->created_at))->locale('pl')->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE);
        return response()->json($user);
    }
    
    public function posts($name)
    {
        $posty = User::where('name', $name)->firstOrFail()->post()->paginate(15);
        return PostResource::collection($posty);
    }
    
    public function entries($name)
    {
        $entries = User::where('name', $name)->firstOrFail()->entry()->paginate(15);
        return EntryResource::collection($entries);
    }
    
    public function tags($name)
    {
        $tags = User::where('name', $name)->firstOrFail()->tag;
        return TagResource::collection($tags);
    }
}
