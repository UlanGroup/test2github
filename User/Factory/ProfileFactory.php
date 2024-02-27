<?php

namespace app\User\Factory;

use app\User\Entity\Profile;
use app\User\Entity\User;

class ProfileFactory
{
    public function create(User $user): ?Profile
    {
        $profile = new Profile();
        $profile->id = $user->id;
        $profile->name = $user->username;
        $profile->email = $user->email;
        $profile->status = 0;
        $profile->save();

        return $profile;
    }


    public function update(array $post): ?Profile
    {
        $profile = Profile::find()->where(['id' => $post['id']])->one();

        if (!empty($post['name'])) { $profile->name = $post['name']; }
        if (!empty($post['secondname'])) { $profile->secondname = $post['secondname']; }
        if (!empty($post['middlename'])) { $profile->middlename = $post['middlename']; }
        if (!empty($post['email'])) { $profile->email = $post['email']; }
        if (!empty($post['phone'])) { $profile->phone = $post['phone']; }
        if (!empty($post['tl'])) { $profile->tl = $post['tl']; }
        if (!empty($post['picture'])) { $profile->picture = $post['picture']; }
        if (!empty($post['status'])) { $profile->status = $post['status']; }

        return $profile;
    }


}
