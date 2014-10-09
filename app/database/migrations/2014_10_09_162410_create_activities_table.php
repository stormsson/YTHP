<?php

use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('activities', function ($table) {
            $table->increments('id');

            $table->string('string_id');
            $table->string('channel_id');
            $table->text('resource');
            $table->string('etag');
            $table->timestamps();

            // We'll need to ensure that MySQL uses the InnoDB engine to
            // support the indexes, other engines aren't affected.
            $table->engine = 'InnoDB';
        });
    }

    /**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('activities');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
