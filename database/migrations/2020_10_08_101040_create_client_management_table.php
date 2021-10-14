<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Helper\Table;

class CreateClientManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->string('token')->unique();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('picture', 255)->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::create('client_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session', 25);
            $table->string('title')->nullable();
            $table->tinyInteger('priority')->nullable(); // 1 = Low, 2 = Medium, 3 = High, 4 = Urgent, 5 = Critical
            $table->string('token')->nullable();
            $table->string('category')->nullable();
            $table->string('resolution_code')->nullable();
            $table->string('solution')->nullable();
            $table->boolean('is_read')->default(false);
            $table->tinyInteger('status')->default(1); // 1 = Open, 2 = Pending, 3 = Resolved, 4 = Closed, 5 = Waiting for Agent, 6 = Waiting for Client
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('client_session_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('hash', 50);
            $table->string('sender');
            $table->mediumText('message')->nullable();
            $table->integer('reply_to')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('client_sessions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('client_session_message_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('message_id');
            $table->string('name');
            $table->string('extension', 10);
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('client_session_messages')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('client_session_taggable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('taggable_id');
            $table->unsignedBigInteger('session_id');
            $table->timestamps();

            $table->foreign('taggable_id')->references('id')->on('taggables');
            $table->foreign('session_id')->references('id')->on('client_sessions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('client_sessions');
        Schema::dropIfExists('client_session_messages');
        Schema::dropIfExists('client_session_message_attachments');
        Schema::dropIfExists('client_session_taggable');
    }
}
