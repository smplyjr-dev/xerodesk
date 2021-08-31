<?php

namespace App\Traits\Chatbot;

use App\Models\Chatbot\Chatbot;
use App\Models\Chatbot\Utterance;

trait ChatbotTrait
{
    public function intents($id)
    {
        $response = [];
        $chatbot = Chatbot::find($id);

        if ($chatbot) {
            foreach ($chatbot->intents as $intentKey => $intent) {
                $response[$intentKey] = array_merge(
                    $intent->toArray(),
                    ['utterances' => $intent->utterances->pluck('body')->toArray()],
                    ['responses' => $intent->responses->pluck('body')->toArray()]
                );
            }
        }

        return response()->json($response, 200);
    }

    public function suggestions($id)
    {
        $response = [];
        $chatbot = Chatbot::find($id);

        if ($chatbot) {
            $response = $chatbot->suggestions;
        }

        return response()->json($response, 200);
    }

    public function message($id)
    {
        $response  = [];
        $message   = request()->message;
        $utterance = Utterance::where('body', 'like', "%$message%")->first();

        if ($utterance) {
            $response = $utterance->intent->responses->pluck('body');
        } else {
            $chatbot  = Chatbot::find($id);
            $response = [$chatbot->clarification_prompt];
        }

        return response()->json($response, 200);
    }
}
