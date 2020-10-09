<?php

namespace App\Services;

use App\Http\Requests\CommentRequest;
use App\Comment;
use App\Plus;
use Helper;

class CommentService {
    /**
     * Store a newly created comment.
     *
     * @param CommentRequest $request
     * @return void
     */
    public static function store(CommentRequest $request)
    {
        $mentioned = Helper::getMentioned($request->body);

        if($request->model == 'post'){
            Helper::addCommentToPost($request->id, $request->body, $mentioned);
        }else if($request->model == 'entry'){
            Helper::addCommentToEntry($request->id, $request->body, $mentioned);
        }else if($request->model == 'comment'){
            Helper::addCommentToComment($request->id, $request->body, $mentioned);
        } 
    }

    /**
     * Update the specified comment.
     *
     * @param Comment $comment
     * @param CommentRequest $request
     * @return Comment
     */
    public static function update(Comment $comment, CommentRequest $request)
    {
        $comment->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $comment->save();

        return $comment;
    }

    /**
     * Remove the specified comment.
     *
     * @param Comment $comment
     * @return void
     */
    public static function delete(Comment $comment)
    {
        $comment->delete();
    }

    /**
     * Plus the specified Comment.
     *
     * @param Comment $comment
     * @return void
     */
    public static function plus(Comment $comment)
    {
        $comment->plus()->save(new Plus());
    }

    /**
     * Unplus the specified Comment.
     *
     * @param Comment $comment
     * @return void
     */
    public static function unPlus(Comment $comment)
    {
        $comment->plus()->where('user_id', auth()->user()->id)->delete();
    }

}
