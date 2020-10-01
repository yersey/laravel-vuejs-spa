<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Wpis;
use App\Post;
use App\Comment;
use App\Http\Resources\CommentResource;
use App\Notifications\Mentioned;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['show']]);
    }

    public function show($id)
    {
        $comment = Comment::FindOrFail($id);
        return new CommentResource($comment);
    }

    public function store(CommentRequest $request){
        preg_match_all('/@\w+/', $request->body, $raw_users);
        $mentioned = null;
        if($raw_users[0]){
            $raw_users = array_unique($raw_users[0]);
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
        }else if($request->model == 'wpis'){
            $wpis = Wpis::findOrFail($request->id);
            $wpis->comment()->save(new Comment(['body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)]));
            if($mentioned){
                Notification::send($mentioned, new Mentioned('comment', $wpis->id, 'wpis'));
            }
        }else{
            $comment = Comment::findOrFail($request->id);
            $comment->comment()->save(new Comment(['body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)]));
            if($mentioned){
                Notification::send($mentioned, new Mentioned('comment', $comment->commentable_id, $comment->commentable_type == 'App\Wpis' ? 'wpis' : 'post'));
            }
        } 
    }

    public function update($id, UpdateCommentRequest $request)
    {
        $comment = Comment::findOrFail($id);

        if($comment->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }

        $comment->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $comment->save();

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if($comment->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Komentarz został pomyślnie usunięty', 'id' => $id]);
    }
}
