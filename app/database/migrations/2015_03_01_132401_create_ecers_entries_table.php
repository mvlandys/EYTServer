<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcersEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ecers_entries', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string("centre");
            $table->string("room");
            $table->string("observer");
            $table->string("study");
            $table->dateTime("played_at")->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        if (Schema::hasTable('ecers_entries')) {
            Schema::drop('ecers_entries');
        }
	}

}
