<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Suggestion;
use App\Traits\Chatbot\SuggestionTrait;

class SuggestionController extends Controller
{
    use SuggestionTrait;

    public function index()
    {
    }

    public function store()
    {
        $suggestion = Suggestion::create([
            'chatbot_id' => request()->chatbot_id,
            'body'       => request()->body,
        ]);

        return response()->json($suggestion, 201);
    }

    public function show($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
        $suggestion = Suggestion::destroy($id);

        $response = [
            'status'  => 'success',
            'message' => 'Your chatbot has been succesfully updated.',
            'data'    => $suggestion
        ];

        return response()->json($response, 200);
    }
}
