<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nanoid');
            $table->string('title')->default("Xerodesk");
            $table->string('sub_title')->default("We connect customers to businesses");
            $table->string('theme_bg')->default('#f68a1f');
            $table->string('theme_color')->default('#ffffff');
            $table->string('text_bg')->default('#e9e9e9');
            $table->string('text_color')->default('#4b4b4b');
            $table->string('button_bg')->default('#6f6f6f');
            $table->string('picture')->default('photograph.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
