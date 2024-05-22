<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->integer('category_id')->nullable();
            $table->bigInteger('items_count')->default(0);
            $table->timestamp('imported_at');
            $table->tinyInteger('status')->default('1')
                ->comment('1 = SUCCESS, 2 = IMPORTING, 3 = FAIL, 4 = DELETED');
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('import_logs');
    }
}
