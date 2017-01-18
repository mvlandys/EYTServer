<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEarlyNumeracyScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('early_numeracy_scores', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer("game_id");
            $table->string("item");
            $table->integer("value");
            $table->string("response");
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
        Schema::dropIfExists('early_numeracy_scores');
	}

}
