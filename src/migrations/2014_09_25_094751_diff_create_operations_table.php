<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DiffCreateOperationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diff_operations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('table1');
            $table->string('table2');
            $table->text('fields')->nullable();
            $table->text('condition')->nullable();
            $table->text('options')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('comment')->nullable();
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
        Schema::drop('diff_operations');
    }


}
