<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Post;
use App\Dig;
use App\Tag;
use App\Http\Resources\PostResource;
use App\Notifications\TagUse;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posty = Post::orderBy('created_at', 'desc')->paginate(15);
        return PostResource::collection($posty);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->imgurl = $request->imgurl;
        $post->user_id = auth()->user()->id;
        $post->save();

        if($request->tags){
            $tags = explode(" ", $request->tags);
            
            foreach($tags as $tag_){
                $tag = Tag::where('name', $tag_)->first();
                if(!$tag){
                    $post->tag()->save(new Tag(['name' => $tag_]));
                }else {
                    $post->tag()->save($tag);
                } 
                $users = auth()->user()->whereHas('Tag', function ($query) use ($tag_) {$query->where('name', $tag_);})->get();
                Notification::send($users, new TagUse('post', $post->id));
            }
        }

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        if($post->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }
        
        $post->title = $request->title;
        $post->body = $request->body;
        $post->imgurl = $request->imgurl;
        $post->save();

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post został pomyślnie usunięty']);
    }

    public function Dig(Post $post)
    {
        if(!$post->isDig() && $post->user->id != auth()->user()->id){
            $post->dig()->save(new Dig());
        }else{
            return response()->json('error', 400);
        }
    }

    public function unDig(Post $post)
    {
        if($post->isDig()){
            $post->dig()->where('user_id', auth()->user()->id)->delete();
        }
    }
}
