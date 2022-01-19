<?php

namespace App\Models\Client;

use App\BaseModel;
use App\Mail\MailSessionLock;
use App\Mail\MailSessionUpdated;
use App\Mail\SendMessages;
use App\Models\Group\Group;
use App\Models\Taggable\Taggable;
use App\Models\User\User;
use App\Traits\Logger;
use Illuminate\Support\Facades\Mail;

class Session extends BaseModel
{
    use Logger;

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
        if ($this->client->email) {
            Mail::to($this->client->email)->send(new SendMessages($this->user, $this->client, $this->messages));
        }
    }

    public function taggables()
    {
        return $this->belongsToMany(Taggable::class, 'client_session_taggable', 'session_id', 'taggable_id')->withTimestamps();
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sendSessionUpdateNotification()
    {
        Mail::to($this->client->email)->send(new MailSessionUpdated($this, 'client'));
    }

    public function sendSessionLockNotification()
    {
        Mail::to($this->client->email)->send(new MailSessionLock($this, 'client'));
    }

    public function logger($type)
    {
        if (!config('activitylog.enabled')) return false;

        if ($type == 'create') {
            $starting = $this->replicate()->setRawAttributes($this->getOriginal())->toArray();
            unset($starting['client']);

            $a = activity();
            $a->on($this);
            $a->by($this->client);
            $a->withProperties(['starting' => $starting, 'ending' => []]);
            $a->useLog('SESSION');
            $a->log('CREATE');
        } else if ($type == 'transcript') {
            $a = activity();
            $a->on($this);
            $a->by(request()->user());
            $a->withProperties(['starting' => [], 'ending' => []]);
            $a->useLog('SESSION');
            $a->log('TRANSCRIPT');
        } else {
            $old = static::$old;
            $dirty = $this->getChanges();
            $starting = [];
            $ending = $dirty;
            foreach ($dirty as $nK => $n) $starting[$nK] = $old[$nK];

            if (in_array($type, ['assign', 'lock', 'status', 'transcript', 'transfer', 'update'])) {
                $a = activity();
                $a->on($this);
                $a->by(request()->user());
                $a->withProperties(['starting' => $starting, 'ending' => $ending]);
                $a->useLog('SESSION');
                $a->log(strtoupper($type));
            }
        }

        return response()->json($a, 200);
    }
}
