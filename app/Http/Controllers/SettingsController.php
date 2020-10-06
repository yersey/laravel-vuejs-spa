<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AvatarRequest;


class SettingsController extends Controller
{
    /**
     * Store avatar
     *
     * @param AvatarRequest $request
     * @return void
     */
    public function uploadAvatar(AvatarRequest $request)
    {
        $name = $request->file('avatar')->store('avatars', 'public');
        auth()->user()->update(['avatar' => $name]);
    }
}
