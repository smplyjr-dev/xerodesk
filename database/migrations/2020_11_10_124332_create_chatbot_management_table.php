<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatbotManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->longText('clarification_prompt');
            $table->integer('max_attempts')->default(3);
            $table->longText('hangup_phrase');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::create('chatbot_intents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chatbot_id');
            $table->string('name');
            $table->longText('custom_attributes');
            $table->timestamps();

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
        });

        Schema::create('chatbot_intent_utterances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('intent_id');
            $table->longText('body');
            $table->timestamps();

            $table->foreign('intent_id')->references('id')->on('chatbot_intents')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('chatbot_intent_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('intent_id');
            $table->longText('body');
            $table->timestamps();

            $table->foreign('intent_id')->references('id')->on('chatbot_intents')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('chatbot_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('chatbot_id');
            $table->longText('body');
            $table->timestamps();

            $table->foreign('chatbot_id')->references('id')->on('chatbots')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbots');
        Schema::dropIfExists('chatbot_intents');
        Schema::dropIfExists('chatbot_intent_utterances');
        Schema::dropIfExists('chatbot_intent_responses');
        Schema::dropIfExists('chatbot_suggestions');
    }
}
