<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClientSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_sessions', function (Blueprint $table) {
            $table->string('category')->nullable()->after('token');
            $table->string('resolution_code')->nullable()->after('category');
            $table->string('solution')->nullable()->after('resolution_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_sessions', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('resolution_code');
            $table->dropColumn('solution');
        });
    }
}
