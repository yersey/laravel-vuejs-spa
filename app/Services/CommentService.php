<?php

namespace App\Services;

use App\Http\Requests\CommentRequest;
use App\Entry;
use App\Comment;
use App\Plus;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Mentioned;

class CommentService {

    /**
     * Store a newly created comment.
     *
     * @param CommentRequest $request
     * @return void
     */
    public static function store(CommentRequest $request)
    {
        preg_match_all('/@\w+/', $request->body, $raw_users);
        $mentioned = null;
        if($raw_users[0]){
            $raw_users = array_unique($raw_users[0]);
            $mention_users = [];
            foreach($raw_users as $mention_user){
                $mention_users[] = substr($mention_user, 1);
            } 
            $mentioned = auth()->user()->whereIn('name', $mention_users)->get();  
        }

        if($request->model == 'post'){
            $post = Post::findOrFail($request->id);
            $post->comment()->save(new Comment(['body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)]));
            if($mentioned){
                Notification::send($mentioned, new Mentioned('comment', $post->id, 'post'));
            }
        }else if($request->model == 'entry'){
            $entry = Entry::findOrFail($request->id);
            $entry->comment()->save(new Comment(['body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)]));
            if($mentioned){
                Notification::send($mentioned, new Mentioned('comment', $entry->id, 'entry'));
            }
        }else if($request->model == 'comment'){
            $comment = Comment::findOrFail($request->id);
            $comment->comment()->save(new Comment(['body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)]));
            if($mentioned){
                Notification::send($mentioned, new Mentioned('comment', $comment->commentable_id, $comment->commentable_type == 'App\Entry' ? 'entry' : 'post'));
            }
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
