<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponseColumnsToRdeApps extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verbal_scores', function(Blueprint $table) {
            $table->string("response");
        });

        Schema::table('numeracy_scores', function(Blueprint $table) {
            $table->string("response");
        });

        Schema::table('numbers_scores', function(Blueprint $table) {
            $table->string("response");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verbal_scores', function(Blueprint $table) {
            $table->dropColumn("response");
        });

        Schema::table('numeracy_scores', function(Blueprint $table) {
            $table->dropColumn("response");
        });

        Schema::table('numbers_scores', function(Blueprint $table) {
            $table->dropColumn("response");
        });
    }

}
