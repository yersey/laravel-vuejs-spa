<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\PostResource;
use App\Http\Resources\EntryResource;
use App\Http\Resources\TagResource;

class ProfileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function show($name)
    {
        $user = User::where('name', $name)->firstOrFail();
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
