<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Models\User\User;

class Message extends BaseModel
{
    protected $table = 'client_session_messages';

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
