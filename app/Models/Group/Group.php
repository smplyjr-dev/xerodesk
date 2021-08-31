<?php

namespace App\Models\Group;

use App\BaseModel;
use App\Models\User\User;

class Group extends BaseModel
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')->withTimestamps();
    }
}
