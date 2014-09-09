<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVocabGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vocab_games', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer("subject_id");
            $table->integer("session_id");
            $table->integer("grade");
            $table->date("dob");
            $table->integer("age");
            $table->integer("sex");
            $table->integer("score");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vocab_games');
	}

}
