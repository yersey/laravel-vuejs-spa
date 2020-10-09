<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarRequest;
use App\Services\SettingsService;


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
        SettingsService::uploadAvatar($request);
    }
}
