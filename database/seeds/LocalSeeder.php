<?php

use App\Models\Client\Client;
use App\Models\Client\Message;
use App\Models\Client\Session;
use App\Models\Taggable\Taggable;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Taggables
        $tag_a = Taggable::create(['name' => 'Tag A']);
        $tag_b = Taggable::create(['name' => 'Tag B']);
        $tag_c = Taggable::create(['name' => 'Tag C']);

        // Client
        $john = Client::create(['company_id' => 1, 'token' => 'token_a', 'email' => 'john@doe.com', 'name' => 'John Doe', 'picture' => 'storage/companies/1/users/1.png']);
        $jane = Client::create(['company_id' => 1, 'token' => 'token_b', 'email' => 'jane@doe.com', 'name' => 'Jane Doe', 'picture' => 'storage/companies/1/users/2.png']);
        $james = Client::create(['company_id' => 1, 'token' => 'token_c', 'email' => 'james@doe.com', 'name' => 'James Doe', 'picture' => 'storage/companies/1/users/3.png']);

        // Client Session
        $session_a = Session::create(['client_id' => $john->id, 'session' => 'session_a', 'title' => 'session_a', 'user_id' => 1]);
        $session_b = Session::create(['client_id' => $jane->id, 'session' => 'session_b', 'title' => 'session_b', 'user_id' => 1]);
        $session_c = Session::create(['client_id' => $james->id, 'session' => 'session_c', 'title' => 'session_c', 'user_id' => 1]);

        // Client Session Tags
        $session_a->taggables()->attach($tag_a->id);
        $session_b->taggables()->attach($tag_b->id);
        $session_c->taggables()->attach($tag_c->id);

        // Client Sesssion Messages
        Message::create(['session_id' => $session_a->id, 'message_from' => 'client', 'message' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, odit. - 1']);
        Message::create(['session_id' => $session_b->id, 'message_from' => 'client', 'message' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, odit. - 2']);
        Message::create(['session_id' => $session_c->id, 'message_from' => 'client', 'message' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, odit. - 3']);
    }
}
