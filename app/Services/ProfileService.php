<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;

 class ProfileService
 {
     /**
      * Return the specified user profile.
      *
      * @param User $user
      * @return User
      */
     public static function show(User $user)
     {
        $user['when'] = (new Carbon($user->created_at))->locale('pl')->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE);

        return $user;
     }

     /**
      * Return the specified user posts.
      *
      * @param User $user
      * @return mixed
      */
     public static function posts(User $user)
     {
        return $user->post()->paginate(15);
     }

     /**
      * Return the specified user entries.
      *
      * @param User $user
      * @return mixed
      */
     public static function entries(User $user)
     {
        return $user->entry()->paginate(15);
     }

     /**
      * Return watched tags for a specified user
      *
      * @param User $user
      * @return mixed
      */
     public static function tags(User $user)
     {
        return $user->tag;
     }

}