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
    public function store(CommentRequest $request){
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
     * @return CommentResource
     */
    public function update(Comment $comment, CommentRequest $request)
    {
        $this->authorize('update', $comment);

        $comment->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $comment->save();

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

        $comment->delete();
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

        $comment->plus()->save(new Plus());
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
        $comment->plus()->where('user_id', auth()->user()->id)->delete();
    }
}
