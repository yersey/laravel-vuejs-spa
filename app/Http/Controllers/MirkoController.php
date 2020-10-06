<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EntryRequest;
use Illuminate\Support\Facades\Validator;
use App\Entry;
use App\Tag;
use App\Plus;
use App\Http\Resources\EntryResource;
use App\Notifications\Mentioned;
use App\Notifications\TagUse;
use Illuminate\Support\Facades\Notification;

class MirkoController extends Controller
{
    /**
     * Return the list of entries.
     *
     * @return mixed
     */
    public function index()
    {
        $entries = Entry::orderBy('created_at', 'desc')->paginate(15);
        return EntryResource::collection($entries);
    }

    /**
     * Store a newly created entry.
     *
     * @param EntryRequest $request
     * @return mixed
     */
    public function store(EntryRequest $request)
    {
        $entry = new Entry();
        $entry->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $entry->user_id = auth()->user()->id;
        $entry->save();

        preg_match_all('/#\w+/', $request->body, $tags);
        if($tags[0]){
            $tags = array_unique($tags[0]);

            foreach($tags as $tag__)
            {
                $tag_ = substr($tag__, 1);

                $rules = [
                    'tag' => 'regex:/^[\pL\s]+$/u',
                ];
                $customMessages = [
                    'tag_.regex'  => 'W tagach znajduja się niedozwolone znaki.',
                ];
                $validator = Validator::make(['tag' => $tag_], $rules, $customMessages);
                if($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                $tag = Tag::where('name', $tag_)->first();
                if(!$tag){
                    $entry->tag()->save(new Tag(['name' => $tag_]));
                }else {
                    $entry->tag()->save($tag);
                } 
                $users = auth()->user()->whereHas('Tag', function ($query) use ($tag_) {$query->where('name', $tag_);})->get();
                Notification::send($users, new TagUse('entry', $entry->id));
            }
        }

        preg_match_all('/@\w+/', $request->body, $raw_users);
        if($raw_users[0]){
            $raw_users = array_unique($raw_users[0]);
            $mention_users = [];
            foreach($raw_users as $mention_user){
                $mention_users[] = substr($mention_user, 1);
            } 
            $mentioned = auth()->user()->whereIn('name', $mention_users)->get();
            Notification::send($mentioned, new Mentioned('entry', $entry->id));
        }

        return new EntryResource($entry);
    }

    /**
     * Return the specified entry.
     *
     * @param Entry $entry
     * @return EntryResource
     */
    public function show(Entry $entry)
    {
        return new EntryResource($entry);
    }

    /**
     * Update the specified entry.
     *
     * @param EntryRequest $request
     * @param Entry $entry
     * @return EntryResource
     */
    public function update(EntryRequest $request, Entry $entry)
    {
        $this->authorize('update', $entry);
        
        $entry->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $entry->save();

        return new EntryResource($entry);
    }

    /**
     * Remove the specified entry.
     *
     * @param  Entry $entry
     * @return void
     */
    public function destroy(Entry $entry)
    {
        $this->authorize('delete', $entry);

        $entry->delete();
    }

    /**
     * Plus the specified entry.
     *
     * @param Entry $entry
     * @return void
     */
    public function plus(Entry $entry)
    {
        $this->authorize('plus', $entry);

        $entry->plus()->save(new Plus());
    }

    /**
     * Unplus the specified entry.
     *
     * @param Entry $entry
     * @return void
     */
    public function unPlus(Entry $entry)
    {
        $this->authorize('unPlus', $entry);

        $entry->plus()->where('user_id', auth()->user()->id)->delete();
    }
}
