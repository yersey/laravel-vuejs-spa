<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Entry;
use App\Post;
use App\Comment;
use App\Plus;
use App\Http\Resources\CommentResource;
use App\Notifications\Mentioned;
use Illuminate\Support\Facades\Notification;

use App\Services\CommentService;

class CommentController extends Controller
{
    /**
     * Return the specified Comment.
     *
     * @param Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Store a newly created comment.
     *
     * @param CommentRequest $request
     * @return void
     */
    public function store(CommentRequest $request)
    {
        CommentService::store($request);
    }

    /**
     * Update the specified comment.
     *
     * @param Comment $comment
     * @param CommentRequest $request
     * @return CommentResource
     */
    public function update(Comment $comment, CommentRequest $request)
    {
        $this->authorize('update', $comment);

        $comment = CommentService::update($comment, $request);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified comment.
     *
     * @param Comment $comment
     * @return void
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        CommentService::delete($comment);
    }

    /**
     * Plus the specified Comment.
     *
     * @param Comment $comment
     * @return void
     */
    public function plus(Comment $comment)
    {
        $this->authorize('plus', $comment);

        CommentService::plus($comment);
    }

    /**
     * Unplus the specified comment.
     *
     * @param Comment $comment
     * @return void
     */
    public function unPlus(Comment $comment)
    {
        $this->authorize('unPlus', $comment);

        CommentService::unPlus($comment);
    }
}
