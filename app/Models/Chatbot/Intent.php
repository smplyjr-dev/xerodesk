<?php

namespace App\Models\Chatbot;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intent extends BaseModel
{
    use SoftDeletes;

    protected $table = 'chatbot_intents';

    protected $casts = [
        'custom_attributes' => 'array'
    ];

    public function chatbot()
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function utterances()
    {
        return $this->hasMany(Utterance::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public static function createIntent($request)
    {
        $intent = self::create([
            'chatbot_id'        => $request['chatbot_id'],
            'name'              => $request['name'],
            'custom_attributes' => $request['custom_attributes'],
        ]);

        foreach ($request['utterances'] as $utterance) {
            $intent->utterances()->create(['body' => $utterance]);
        }

        foreach ($request['responses'] as $response) {
            $intent->responses()->create(['body' => $response]);
        }

        return array_merge(
            $intent->toArray(),
            ['utterances' => $intent->utterances->pluck('body')->toArray()],
            ['responses' => $intent->responses->pluck('body')->toArray()]
        );
    }

    public static function updateIntent($request, $id)
    {
        $intent = self::find($id);

        $intent = tap($intent)->update([
            'custom_attributes' => $request['custom_attributes'],
        ]);

        // delete all previous utterances
        $intent->utterances()->delete();

        // create the new batch of utterances
        foreach ($request['utterances'] as $utterance) {
            $intent->utterances()->create(['body' => $utterance]);
        }

        // delete all previous responses
        $intent->responses()->delete();

        // create the new batch of utterances
        foreach ($request['responses'] as $response) {
            $intent->responses()->create(['body' => $response]);
        }

        return array_merge(
            $intent->toArray(),
            ['utterances' => $intent->utterances->pluck('body')->toArray()],
            ['responses' => $intent->responses->pluck('body')->toArray()]
        );
    }
}
