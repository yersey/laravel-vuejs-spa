<?php

namespace App\Helpers;
use App\Post;
use App\Entry;
use App\Tag;
use App\Comment;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Mentioned;
use App\Notifications\TagUse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;


class Helper
{

    /**
     * Return mentioned users
     *
     * @param string $body
     * @return Collection
     */
    public static function getMentioned(string $body)
    {
        $mentioned = null;
        preg_match_all('/@\w+/', $body, $raw_users);
        if($raw_users[0]){
            $raw_users = array_unique($raw_users[0]);
            $mention_users = [];
            foreach($raw_users as $mention_user){
                $mention_users[] = substr($mention_user, 1);
            } 
            $mentioned = auth()->user()->whereIn('name', $mention_users)->get();  
        }

        return $mentioned;
    }

    /**
     * Return tag name list
     *
     * @param string $body
     * @return array
     */
    public static function getUsedTagsNames(string $body)
    {
        $tagList = [];
        preg_match_all('/#\w+/', $body, $tags);
        if($tags[0]){
            $tags = array_unique($tags[0]);
            foreach($tags as $tag)
            {
                $tagList[] = substr($tag, 1);
            }
        }
        
        return $tagList;
    }

    /**
     * Create entry with sanitized data
     *
     * @param string $body
     * @return Entry
     */
    public static function createEntry(string $body)
    {
        $entry = new Entry();
        $entry->body = filter_var($body, FILTER_SANITIZE_SPECIAL_CHARS);
        $entry->user_id = auth()->user()->id;
        $entry->save();

        return $entry;
    }

    /**
     * Add comment to post and notify mentioned users
     *
     * @param integer $id
     * @param string $body
     * @param Collection $mentioned
     * @return void
     */
    public static function addCommentToPost(int $id, string $body, Collection $mentioned = null)
    {
        $post = Post::findOrFail($id);
        $post->comment()->save(new Comment(['body' => filter_var($body, FILTER_SANITIZE_SPECIAL_CHARS)]));
        if($mentioned){
            Notification::send($mentioned, new Mentioned('comment', $post->id, 'post'));
        }
    }

    /**
     * Add comment to entry and notify mentioned users
     *
     * @param integer $id
     * @param string $body
     * @param Collection $mentioned
     * @return void
     */
    public static function addCommentToEntry(int $id, string $body, Collection $mentioned = null)
    {
        $entry = Entry::findOrFail($id);
        $entry->comment()->save(new Comment(['body' => filter_var($body, FILTER_SANITIZE_SPECIAL_CHARS)]));
        if($mentioned){
            Notification::send($mentioned, new Mentioned('comment', $entry->id, 'entry'));
        }
    }

    /**
     * Add comment to comment and notify mentioned users
     *
     * @param integer $id
     * @param string $body
     * @param Collection $mentioned
     * @return void
     */
    public static function addCommentToComment(int $id, string $body, Collection $mentioned = null)
    {
        $comment = Comment::findOrFail($id);
        $comment->comment()->save(new Comment(['body' => filter_var($body, FILTER_SANITIZE_SPECIAL_CHARS)]));
        if($mentioned){
            Notification::send($mentioned, new Mentioned('comment', $comment->commentable_id, $comment->commentable_type == 'App\Entry' ? 'entry' : 'post'));
        }
    }

    /**
     * Validate tag
     *
     * @param string $tag
     * @return mixed
     */
    public static function validateTag(string $tag)
    {
        $rules = [
            'tag' => 'alpha_num',
        ];
        $customMessages = [
            'tag.alpha_num'  => 'W tagach znajduja się niedozwolone znaki.',
        ];
        $validator = Validator::make(['tag' => $tag], $rules, $customMessages);
        if($validator->fails()) {
            return false;
        }

        return true;
    }

    /**
     * Add tag to entry (create if doesn't exist)
     *
     * @param string $tagName
     * @param Entry $entry
     * @return void
     */
    public static function addTagToEntry(string $tagName, Entry $entry)
    {
        $tag = Tag::where('name', $tagName)->first();
        if(!$tag){
            $tag = new Tag(['name' => $tagName]);
        }

        if(!$entry->tag()->where('name', $tagName)->exists()){
            $entry->tag()->save($tag);
        } 
    }

    /**
     * Return users following specified tag
     *
     * @param string $tag
     * @return Collection
     */
    public static function getUsersFollowingTag(string $tag)
    {
        $users = auth()->user()->whereHas('Tag', function ($query) use ($tag) {$query->where('name', $tag);})->get();

        return $users;
    }
    
    /**
     * Create new Post
     *
     * @param string $title
     * @param string $body
     * @param string $imgurl
     * @return Post
     */
    public static function createPost(string $title, string $body, string $imgurl)
    {
        $post = new Post();
        $post->title = $title;
        $post->body = $body;
        $post->imgurl = $imgurl;
        $post->user_id = auth()->user()->id;
        $post->save();

        return $post;
    }

    /**
     * Add tag to Post (create if doesn't exist)
     *
     * @param string $tagName
     * @param Post $post
     * @return void
     */
    public static function addTagToPost(string $tagName, Post $post)
    {
        $tag = Tag::where('name', $tagName)->first();
        if(!$tag){
            $tag = new Tag(['name' => $tagName]);
        }

        if(!$post->tag()->where('name', $tagName)->exists()){
            $post->tag()->save($tag);
        } 
    }
}