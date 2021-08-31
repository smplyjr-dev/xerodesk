<?php

namespace App\Models\User;

use App\BaseModel;

class UserBio extends BaseModel
{
    protected $table = 'user_bio';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
