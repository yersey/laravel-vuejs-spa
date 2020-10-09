<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;

class PostController extends Controller
{
    /**
     * Return a list of posts.
     *
     * @return mixed
     */
    public function index()
    {
        $posts = PostService::index();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created Post
     *
     * @param PostRequest $request
     * @return PostResource
     */
    public function store(PostRequest $request)
    {
        $post = PostService::store($request);

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
        
        $post = PostService::update($request, $post);

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

        PostService::delete($post);
    }

    /**
     * Dig the specified post
     *
     * @param Post $post
     * @return void
     */
    public function dig(Post $post)
    {
        $this->authorize('dig', $post);

        PostService::dig($post);
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

        PostService::unDig($post);
    }
}
