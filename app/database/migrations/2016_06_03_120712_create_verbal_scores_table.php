<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerbalScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('verbal_scores', function(Blueprint $table)
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
		Schema::dropIfExists('verbal_scores');
	}

}
