<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCardsortGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cardsort_games', function(Blueprint $table)
		{
		    $table->increments("id");
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->date("dob")->nullable();
            $table->integer("age")->nullable();
            $table->integer("sex")->nullable();
            $table->dateTime("played_at")->nullable();
            $table->datetime("ts_start")->nullable();
            $table->datetime("ts_lvl1_start")->nullable();
            $table->datetime("ts_lvl1_end")->nullable();
            $table->datetime("ts_lvl2_start")->nullable();
            $table->datetime("ts_lvl2_end")->nullable();
            $table->datetime("ts_lvl3_start")->nullable();
            $table->datetime("ts_lvl3_end")->nullable();
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
        if (Schema::hasTable('cardsort_scores')) {
            Schema::drop('cardsort_scores');
        }

        if (Schema::hasTable('cardsort_games')) {
            Schema::drop('cardsort_games');
        }
	}

}
