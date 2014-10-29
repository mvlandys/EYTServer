<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSexColumnOnNotthisGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('notthis_games', function(Blueprint $table)
        {
            $table->dropColumn("sex");
        });

        Schema::table('notthis_games', function(Blueprint $table)
        {
            $table->string("sex")->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
