<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController extends Controller
{
    /**
     * Return user notfications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications(){
        $notifications = UserService::notifications();

        return response()->json($notifications);
    }

    /**
     * Mark user notifications as read.
     *
     * @param string $id
     * @return void
     */
    public function notification_mark_as_read(string $id){
        UserService::notification_mark_as_read($id);
    }
}
