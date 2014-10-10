<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('google_id');
            $table->string('email');
            $table->string('password');
            $table->text('google_refresh_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('active')->default(0);

            $table->dateTime('last_login')->nullable();

            $table->dateTime('last_yt_subscriptions_update')->nullable();
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
        Schema::drop('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
