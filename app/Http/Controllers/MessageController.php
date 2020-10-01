<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\User;
use App\Message;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function store(MessageRequest $request)
    {
        User::findOrFail($request->to_id);

        if(auth()->user()->id < $request->to_id){
            $conversation = auth()->user()->id."-".$request->to_id;
        }else{
            $conversation = $request->to_id."-".auth()->user()->id;
        }
        
        $message = new Message();
        $message->message = $request->message;
        $message->user_id = auth()->user()->id;
        $message->to_id = $request->to_id;
        $message->conversation = $conversation;
        $message->save();
    }

    public function conversations()
    {
        $conversations = Message::where('to_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->OrderBy('created_at', 'DESC')->get()->unique('conversation');

        return MessageResource::collection($conversations);
    }

    public function conversation($conversation_id = null)
    {
        $me = auth()->user();

        if($conversation_id){
            $ids = explode("-", $conversation_id);
            if(User::findOrFail($ids[0])!= $me && User::findOrFail($ids[1]) != $me){
                return response()->json('Forbidden.', 403);
            }
        }
        
        if($conversation_id){
            $conversation = Message::where('conversation', $conversation_id)->get();
            Message::where('conversation', $conversation_id)->where('to_id', auth()->user()->id)->update(array('read_at' => now()));
        }else{
            $id = Message::where('to_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->OrderBy('created_at', 'DESC')->get()->first()->conversation;
            $conversation = Message::where('conversation', $id)->get();
            Message::where('conversation', $id)->where('to_id', auth()->user()->id)->update(array('read_at' => now()));
        }
        
        return response()->json(['messages' => MessageResource::collection($conversation), 'user' => MessageResource::collection($conversation)[0]]);
    }
}
