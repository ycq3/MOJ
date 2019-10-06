<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContestProblemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contest_problem', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('contest_id')->nullable();
			$table->integer('problem_id')->nullable();
			$table->integer('fake')->nullable();
			$table->integer('accepted')->nullable();
			$table->integer('submited')->nullable();
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
		Schema::drop('contest_problem');
	}

}
