<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WpisRequest;
use Illuminate\Support\Facades\Validator;
use App\Wpis;
use App\Tag;
use App\Http\Resources\WpisResource;
use App\Notifications\Mentioned;
use App\Notifications\TagUse;
use Illuminate\Support\Facades\Notification;

class MirkoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wpisy = Wpis::orderBy('created_at', 'desc')->paginate(15);
        return WpisResource::collection($wpisy);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WpisRequest $request)
    {
        $wpis = new Wpis();
        $wpis->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $wpis->user_id = auth()->user()->id;
        $wpis->save();

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
                    $wpis->tag()->save(new Tag(['name' => $tag_]));
                }else {
                    $wpis->tag()->save($tag);
                } 
                $users = auth()->user()->whereHas('Tag', function ($query) use ($tag_) {$query->where('name', $tag_);})->get();
                Notification::send($users, new TagUse('wpis', $wpis->id));
            }
        }

        preg_match_all('/@\w+/', $request->body, $raw_users);
        if($raw_users[0]){
            $raw_users = array_unique($raw_users[0]);
            foreach($raw_users as $mention_user){
                $mention_users[] = substr($mention_user, 1);
            } 
            $mentioned = auth()->user()->whereIn('name', $mention_users)->get();
            //return response($mentioned);
            Notification::send($mentioned, new Mentioned('wpis', $wpis->id));
        }

        return new WpisResource($wpis);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $wpis = Wpis::FindOrFail($id);
        return new WpisResource($wpis);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WpisRequest $request, $id)
    {
        $wpis = Wpis::findOrFail($id);

        if($wpis->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }
        
        $wpis->body = filter_var($request->body, FILTER_SANITIZE_SPECIAL_CHARS);
        $wpis->save();


        return new WpisResource($wpis);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wpis = Wpis::findOrFail($id);

        if($wpis->user_id != auth()->user()->id){
            return response()->json('Forbidden.', 403);
        }

        $wpis->delete();

        return response()->json(['message' => 'Wpis został pomyślnie usunięty', 'id' => $id]);
    }
}
