<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function uploadAvatar(Request $request)
    {
        $rules = [
            'avatar' => 'required|image|max:2048',
        ];
    
        $customMessages = [
            'avatar.required' => 'Plik jest wymagany.',
            'avatar.image' => 'Plik nie jest obrazem',
            'avatar.max' => 'Plik jest za duży',
        ];
    
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->hasFile('avatar')){
            $name = $request->file('avatar')->store('avatars', 'public');
            auth()->user()->update(['avatar' => $name]);
        }
    }
}
