<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\Chatbot;
use App\Traits\Chatbot\ChatbotTrait;

class ChatbotController extends Controller
{
    use ChatbotTrait;

    public function index()
    {
    }

    public function store()
    {
    }

    public function show($id)
    {
        $chatbot = Chatbot::find($id);

        return response()->json($chatbot, 200);
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
    }
}
