<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Mail\SendMessages;
use App\Models\Group\Group;
use App\Models\Taggable\Taggable;
use App\Models\User\User;
use Illuminate\Support\Facades\Mail;

class Session extends BaseModel
{
    protected $table = 'client_sessions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function sendSessionMessagesNotification()
    {
        if ($this->client->email) Mail::to($this->client->email)->send(new SendMessages($this->user, $this->client, $this->messages));
    }

    public function taggables()
    {
        return $this->belongsToMany(Taggable::class, 'client_session_taggable', 'session_id', 'taggable_id')->withTimestamps();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
