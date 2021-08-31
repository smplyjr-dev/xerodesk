<?php

namespace App\Models\Chatbot;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatbot extends BaseModel
{
    use SoftDeletes;

    protected $table = 'chatbots';

    protected $casts = [
        'clarification_prompt' => 'array',
        'hangup_phrase'        => 'array',
    ];

    public function intents()
    {
        return $this->hasMany(Intent::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
