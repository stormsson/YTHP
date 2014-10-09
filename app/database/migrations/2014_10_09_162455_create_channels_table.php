<?php

use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('channels', function ($table) {
            $table->increments('id');
            $table->string('string_id');

            $table->text('resource');

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
        Schema::drop('channels');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
