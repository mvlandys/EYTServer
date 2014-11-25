<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotthisGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('notthis_games', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->string("dob")->nullable();
            $table->integer("age")->nullable();
            $table->integer("sex")->nullable();
            $table->dateTime("played_at")->nullable();
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
        if (Schema::hasTable('notthis_scores')) {
            Schema::drop('vocab_scores');
        }

        if (Schema::hasTable('notthis_games')) {
            Schema::drop('vocab_games');
        }
	}

}
