<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFishsharkScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fishshark_scores', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer("game_id");
            $table->foreign('game_id')->references('id')->on('fishshark_games');
            $table->integer("level");
            $table->integer("part");
            $table->integer("value");
            $table->string("responseTime");
            $table->string("blankTime");
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
		Schema::drop('fishshark_scores');
	}

}
