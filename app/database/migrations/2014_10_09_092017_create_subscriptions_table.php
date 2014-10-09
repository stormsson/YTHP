<?php

use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('subscriptions', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('channel_id');
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
        Schema::drop('subscriptions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
