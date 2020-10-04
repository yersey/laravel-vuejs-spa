<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function notifications(){
        return response()->json(auth()->user()->notifications);
    }

    public function notification_mark_as_read($id){
        return auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }
}
