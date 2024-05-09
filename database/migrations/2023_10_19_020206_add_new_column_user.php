<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnUser extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('profile_id')->after('id')->nullable();          
            $table->string('first_name')->after('profile_id');
            $table->string('last_name')->after('first_name');
            $table->string('avatar')->after('password')->nullable();
            $table->tinyInteger('gender')->after('avatar')->default(0);
            $table->dateTime('birthday')->after('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_id');          
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('avatar');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
        });
    }
}
