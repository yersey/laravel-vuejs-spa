<?php

namespace App\Services;
use App\Http\Requests\AvatarRequest;

class SettingsService
{
    /**
     * Store avatar
     *
     * @param AvatarRequest $request
     * @return void
     */
    public static function uploadAvatar(AvatarRequest $request)
    {
        $name = $request->file('avatar')->store('avatars', 'public');
        auth()->user()->update(['avatar' => $name]);
    }
}