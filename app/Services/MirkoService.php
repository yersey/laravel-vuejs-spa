<?php

namespace App\Services;

use App\Entry;
use App\Plus;
use App\Http\Requests\EntryRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TagUse;
use App\Notifications\Mentioned;
use Helper;

class MirkoService
{
    /**
     * Return the list of entries
     *
     * @return mixed
     */
    public static function index()
    {
        return Entry::orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Store a newly created entry.
     *
     * @param EntryRequest $request
     * @return Entry
     */
    public static function store(EntryRequest $request)
    {
        $entry = Helper::createEntry($request->body);
        
        $tags = Helper::getUsedTagsNames($request->body);
        foreach($tags as $tag){
            if(Helper::validateTag($tag)){
                Helper::addTagToEntry($tag, $entry);
            }
            $users = Helper::getUsersFollowingTag($tag);
            if(!empty($users)){
                Notification::send($users, new TagUse('entry', $entry->id));
            }
        }

        $users = Helper::getMentioned($request->body);
        if(!empty($users)){
            Notification::send($users, new Mentioned('entry', $entry->id));  
        }
        
        return $entry;
    }

   /**
    * Update the specified entry.
    *
    * @param EntryRequest $request
    * @param Entry $entry
    * @return Entry
    */
    public static function update(EntryRequest $request, Entry $entry)
    {
        $entry->update([
            'body' => filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS)
        ]);

        return $entry;
    }

    /**
     * Remove the specified entry.
     *
     * @param Entry $entry
     * @return void
     */
    public static function delete(Entry $entry)
    {
        $entry->delete();
    }

    /**
     * Plus the specified entry
     *
     * @param Entry $entry
     * @return void
     */
    public static function plus(Entry $entry)
    {
        $entry->plus()->save(new Plus());
    }

    /**
     * Unplus the specified entry
     *
     * @param Entry $entry
     * @return void
     */
    public static function unPlus(Entry $entry)
    {
        $entry->plus()->where('user_id', auth()->user()->id)->delete();
    }
}