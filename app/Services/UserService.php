<?php

namespace App\Services;

class UserService
{
    /**
     * Return user notifications;
     *
     * @return mixed
     */
    public static function notifications()
    {
        return auth()->user()->notifications;
    }

    /**
     * Mark user notifications as read.
     *
     * @param string $id
     * @return void
     */
    public static function notification_mark_as_read(string $id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }
}