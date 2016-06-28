<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNumbersScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('numbers_scores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer("game_id");
			$table->integer("level");
			$table->integer("part");
			$table->integer("value");
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
		Schema::dropIfExists('numbers_scores');
	}

}
