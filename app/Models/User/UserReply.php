<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserReply extends Model
{
    protected $table = 'user_replies';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
