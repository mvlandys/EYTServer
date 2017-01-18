<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEcersQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ecers_questions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer("game_id");
			$table->string("study");
			$table->string("item");
			$table->text("question");
			$table->text("answer");
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
		Schema::drop('ecers_questions');
	}

}
