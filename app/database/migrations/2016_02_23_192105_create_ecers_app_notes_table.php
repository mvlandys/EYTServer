<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcersAppNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ecers_app_notes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer("game_id");
			$table->text("note");
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
		if (Schema::hasTable('ecers_app_notes')) {
			Schema::drop('ecers_app_notes');
		}
	}

}
