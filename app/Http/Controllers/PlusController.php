<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Wpis;
use App\Comment;
use App\Plus;

class PlusController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function store(Request $request){
        $rules = [
            'id' => 'required|integer',
            'model' => 'required|in:wpis,comment'
        ];
    
        $customMessages = [
            //
        ];
    
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->model == 'wpis'){
            $wpis = Wpis::findOrFail($request->id);

            if(!$wpis->isPlus() && $wpis->user->id != auth()->user()->id){
                $wpis->plus()->save(new Plus());
            }else{
                return response()->json('Nie można dać plusa', 400);
            }
        }else{
            $comment = Comment::findOrFail($request->id);

            if(!$comment->isPlus() && $comment->user->id != auth()->user()->id){
                $comment->plus()->save(new Plus());
            }else{
                return response()->json('Nie można dać plusa', 400);
            }
        } 
    }

    public function destroy($model, $id)
    {
        $rules = [
            'id' => 'required|integer',
            'model' => 'required|in:wpis,comment'
        ];

        $validator = Validator::make(['model' => $model, 'id' => $id], $rules);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($model == 'wpis'){
            $wpis = Wpis::findOrFail($id);

            if($wpis->isPlus()){
                $wpis->plus()->where('user_id', auth()->user()->id)->delete();
            }else{
                return response()->json('Nie można usunąć plusa', 400);
            }
        }else{
            $comment = Comment::findOrFail($id);

            if($comment->isPlus()){
                $comment->plus()->where('user_id', auth()->user()->id)->delete();
            }else{
                return response()->json('Nie można usunąć plusa', 400);
            }
        }
    }
}
