<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserReadableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_readable', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('readable_id')->unsigned();
            $table->string('readable_type');
            $table->primary(['user_id','readable_id','readable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_readable');
    }
}
