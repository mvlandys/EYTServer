 <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsortScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cardsort_scores', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer("game_id");
            $table->foreign('game_id')->references('id')->on('cardsort_games');
            $table->integer("level");
            $table->integer("correct");
            $table->integer("incorrect");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('cardsort_scores');
	}

}
