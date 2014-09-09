<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVocabScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vocab_scores', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer("game_id");
            $table->foreign('game_id')->references('id')->on('vocab_games');
            $table->integer("card");
            $table->integer("value");
            $table->string("additional");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vocab_scores');
	}

}
