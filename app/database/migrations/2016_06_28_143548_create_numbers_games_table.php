<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNumbersGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('numbers_games', function(Blueprint $table)
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
		Schema::dropIfExists('numbers_games');
	}

}
