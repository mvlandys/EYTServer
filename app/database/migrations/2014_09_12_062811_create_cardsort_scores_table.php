 <?php

 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Schema;

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
            $table->integer("level");
            $table->integer("card");
            $table->integer("value");
            $table->time("response")->nullable();
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
	}

}
