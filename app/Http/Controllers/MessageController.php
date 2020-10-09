<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\User;
use App\Message;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    /**
     * Store a newly created message.
     *
     * @param MessageRequest $request
     * @return void
     */
    public function store(MessageRequest $request)
    {
        $user = User::where('name', $request->to_id)->firstOrFail();
        $to_id = $user->id; 

        if(auth()->user()->id < $to_id){
            $conversation = auth()->user()->id."-".$to_id;
        }else{
            $conversation = $to_id."-".auth()->user()->id;
        }
        
        $message = new Message();
        $message->message = $request->message;
        $message->user_id = auth()->user()->id;
        $message->to_id = $to_id;
        $message->conversation = $conversation;
        $message->save();
    }

    /**
     * Return a list of conversations.
     *
     * @return mixed
     */
    public function conversations()
    {
        $conversations = Message::where('to_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->OrderBy('created_at', 'DESC')->get()->unique('conversation');

        return MessageResource::collection($conversations);
    }

    /**
     * Return the specified conversation.
     *
     * @param string $conversation_id
     * @return mixed
     */
    public function conversation(string $conversation_id = null)
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
            $conv = Message::where('to_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->OrderBy('created_at', 'DESC')->first();
            if($conv){
                $conversation = Message::where('conversation', $conv->conversation)->get();
                Message::where('conversation', $conv->conversation)->where('to_id', auth()->user()->id)->update(array('read_at' => now()));
                return response()->json(['messages' => MessageResource::collection($conversation), 'user' => MessageResource::collection($conversation)->first()]);
            }else{
                return response()->json(['messages' => MessageResource::collection([]), 'user' => MessageResource::collection([])]);
            }
            
        }
		return response()->json(['messages' => MessageResource::collection($conversation), 'user' => MessageResource::collection($conversation)->first()]);
    }
}
