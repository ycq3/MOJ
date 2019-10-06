<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProblemSubmitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problem_submits', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable();
			$table->integer('problem_id')->nullable();
			$table->integer('contest_id')->nullable();
			$table->integer('language')->nullable();
			$table->text('code', 65535)->nullable();
			$table->string('fetch', 20)->nullable();
			$table->integer('memory')->nullable();
			$table->integer('time')->nullable();
			$table->integer('result')->nullable();
			$table->float('pass_rate', 10, 0)->nullable();
			$table->string('error')->nullable();
			$table->string('pass_detail', 1000)->nullable();
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
		Schema::drop('problem_submits');
	}

}
