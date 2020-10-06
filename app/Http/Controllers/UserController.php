<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return user notfications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications(){
        return response()->json(auth()->user()->notifications);
    }

    /**
     * Mark user notifications as read.
     *
     * @param string $id
     * @return void
     */
    public function notification_mark_as_read(string $id){
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }
}
