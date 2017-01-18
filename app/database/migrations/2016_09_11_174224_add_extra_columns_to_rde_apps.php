<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsToRdeApps extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verbal_games', function(Blueprint $table) {
            $table->string("centre");
            $table->string("assessor");
        });

        Schema::table('numeracy_games', function(Blueprint $table) {
            $table->string("centre");
            $table->string("assessor");
        });

        Schema::table('numbers_games', function(Blueprint $table) {
            $table->string("centre");
            $table->string("assessor");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verbal_games', function(Blueprint $table) {
            $table->dropColumn("centre");
            $table->dropColumn("assessor");
        });

        Schema::table('numeracy_games', function(Blueprint $table) {
            $table->dropColumn("centre");
            $table->dropColumn("assessor");
        });

        Schema::table('numbers_games', function(Blueprint $table) {
            $table->dropColumn("centre");
            $table->dropColumn("assessor");
        });
    }

}
