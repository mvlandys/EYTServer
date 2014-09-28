<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnaireTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->string("dob")->nullable();
            $table->string("age")->nullable();
            $table->string("sex")->nullable();
            $table->string("played_at")->nullable();
            $table->string("type")->nullable();
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

        if (Schema::hasTable('questionnaire')) {
            Schema::drop('questionnaire');
        }
	}

}
