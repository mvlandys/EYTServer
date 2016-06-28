<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerbalGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('verbal_games', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string("child_id");
			$table->string("session_id")->nullable();
			$table->string("test_name")->nullable();
			$table->string("grade")->nullable();
			$table->date("dob")->nullable();
			$table->integer("age")->nullable();
			$table->integer("sex")->nullable();
			$table->dateTime("played_at")->nullable();
			$table->integer("score");
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
		Schema::dropIfExists('verbal_games');
	}

}
