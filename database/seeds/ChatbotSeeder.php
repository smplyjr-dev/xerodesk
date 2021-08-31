<?php

use App\Models\Chatbot\Chatbot;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chatbot::create([
            'user_id'              => 1,
            'name'                 => 'Live Support',
            'clarification_prompt' => ["I didn't get what you mean. Please rephrase it.", "Sorry, I didn't get that. Can you rephrase it?", "Pardon me, could you please rephrase that?"],
            'max_attempts'         => 3,
            'hangup_phrase'        => ["Sorry, I could not assist at the moment. Please try again later. Thank you!"]
        ]);
    }
}
