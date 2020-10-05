<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Entry;
use App\Http\Resources\CommentResource;

class TestController extends Controller
{
    public function index(Request $request)
    {
        //

        return response()->json();
    }
}
