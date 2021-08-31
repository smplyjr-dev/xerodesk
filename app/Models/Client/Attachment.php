<?php

namespace App\Models\Client;

use App\BaseModel;

class Attachment extends BaseModel
{
    protected $table = 'client_session_message_attachments';

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
