<?php

namespace App\Models\Chatbot;

use App\BaseModel;

class Utterance extends BaseModel
{
    protected $table = 'chatbot_intent_utterances';

    public function intent()
    {
        return $this->belongsTo(Intent::class);
    }
}
