<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exams', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title')->nullable();
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
			$table->string('password')->nullable();
			$table->integer('type')->unsigned()->nullable()->default(0);
			$table->integer('rank_control')->nullable();
			$table->integer('is_client_login')->nullable();
			$table->string('classes')->nullable();
			$table->integer('auto_correct')->nullable();
			$table->string('written_set')->nullable();
			$table->integer('invigilate')->nullable();
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
		Schema::drop('exams');
	}

}
