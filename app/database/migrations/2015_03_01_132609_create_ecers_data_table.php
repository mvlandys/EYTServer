<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcersDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ecers_data', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer("entry_id");
            $table->string("test");
            $table->integer("page");
            $table->integer("item");
            $table->integer("item_num");
            $table->integer("value");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        if (Schema::hasTable('ecers_data')) {
            Schema::drop('ecers_data');
        }
	}

}