<?php

namespace App\Models\Client;

use App\BaseModel;

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
}
