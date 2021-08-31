<?php

namespace App\Models\Chatbot;

use App\BaseModel;

class Suggestion extends BaseModel
{
    protected $table = 'chatbot_suggestions';

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }
}
