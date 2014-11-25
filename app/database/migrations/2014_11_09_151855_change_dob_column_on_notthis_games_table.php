<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDobColumnOnNotthisGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Config::get('database')['default'] === 'mysql'){
            // Mysql
            DB::statement('ALTER TABLE notthis_games CHANGE dob dob VARCHAR(255)');
        } else if (Config::get('database')['default'] === 'pgsql'){
            // PostgreSQL
            DB::statement('ALTER TABLE notthis_games ALTER dob TYPE VARCHAR(255)');
        }
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
