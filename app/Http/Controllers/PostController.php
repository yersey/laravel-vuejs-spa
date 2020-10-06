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
     * Return a list of posts.
     *
     * @return mixed
     */
    public function index()
    {
        $posty = Post::orderBy('created_at', 'desc')->paginate(15);
        return PostResource::collection($posty);
    }

    /**
     * Store a newly created Post
     *
     * @param PostRequest $request
     * @return PostResource
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
     * Return the specified post.
     *
     * @param  Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified post.
     *
     * @param PostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $post->title = $request->title;
        $post->body = $request->body;
        $post->imgurl = $request->imgurl;
        $post->save();

        return new PostResource($post);
    }

    /**
     * Remove the specified post
     *
     * @param Post $post
     * @return void
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
    }

    /**
     * Dig the specified post
     *
     * @param Post $post
     * @return void
     */
    public function Dig(Post $post)
    {
        $this->authorize('dig', $post);

        $post->dig()->save(new Dig());
    }

    /**
     * Undig the specified post
     *
     * @param Post $post
     * @return void
     */
    public function unDig(Post $post)
    {
        $this->authorize('unDig', $post);

        $post->dig()->where('user_id', auth()->user()->id)->delete();
    }
}
