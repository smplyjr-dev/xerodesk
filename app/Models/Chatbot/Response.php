<?php

namespace App\Models\Chatbot;

use App\BaseModel;

class Response extends BaseModel
{
    protected $table = 'chatbot_intent_responses';

    protected $casts = [
        'body' => 'array'
    ];

    public function intent()
    {
        return $this->belongsTo(Intent::class);
    }
}
