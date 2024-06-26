<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_translate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("post_id");
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('locale')->index();
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_translate');
    }
}
