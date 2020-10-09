<?php

namespace App\Services;

use App\Post;
use App\Dig;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TagUse;
use Helper;

class PostService
{
    /**
     * Return a list of posts.
     *
     * @return mixed
     */
    public static function index()
    {
        return Post::orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Store a newly created Post
     *
     * @param PostRequest $request
     * @return Post
     */
    public static function store(PostRequest $request)
    {
        $post = Helper::createPost($request->title, $request->body, $request->imgurl);

        $tags = Helper::getUsedTagsNames(is_null($request->tags) ? ' ' : $request->tags);
        foreach($tags as $tag){
            if(Helper::validateTag($tag)){
                Helper::addTagToPost($tag, $post);
            }
            $users = Helper::getUsersFollowingTag($tag);
            if(!empty($users)){
                Notification::send($users, new TagUse('post', $post->id));
            }
        }

        return $post;
    }

    /**
     * Update the specified post.
     *
     * @param PostRequest $request
     * @param Post $post
     * @return Post
     */
    public static function update(PostRequest $request, Post $post)
    {
        $post->title = $request->title;
        $post->body = $request->body;
        $post->imgurl = $request->imgurl;
        $post->save();

        return $post;
    }
    
    /**
     * Remove the specified Post
     *
     * @param Post $post
     * @return void
     */
    public static function delete(Post $post)
    {
        $post->delete();
    }

    /**
     * Dig the specified post
     *
     * @param Post $post
     * @return void
     */
    public static function dig(Post $post)
    {
        $post->dig()->save(new Dig());
    }

    /**
     * Undig the specified post
     *
     * @param Post $post
     * @return void
     */
    public static function unDIg(Post $post)
    {
        $post->dig()->where('user_id', auth()->user()->id)->delete();
    }
}
