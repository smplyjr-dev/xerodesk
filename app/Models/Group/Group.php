<?php

namespace App\Models\Group;

use App\BaseModel;
use App\Models\Client\Session;
use App\Models\User\User;

class Group extends BaseModel
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
