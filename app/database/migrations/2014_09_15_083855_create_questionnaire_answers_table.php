<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnaireAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_answers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer("game_id");
            $table->foreign('game_id')->references('id')->on('questionnaire');
            $table->integer("question");
            $table->integer("answer");
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
        if (Schema::hasTable('questionnaire_answers')) {
            Schema::drop('questionnaire_answers');
        }
	}

}
