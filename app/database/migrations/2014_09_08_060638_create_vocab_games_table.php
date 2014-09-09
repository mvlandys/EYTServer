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
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("grade")->nullable();
            $table->date("dob")->nullable();
            $table->integer("age")->nullable();
            $table->integer("sex")->nullable();
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
