<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

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
        if (Schema::hasTable('vocab_scores')) {
            Schema::drop('vocab_scores');
        }
	}

}
