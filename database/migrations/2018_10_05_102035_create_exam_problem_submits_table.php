<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExamProblemSubmitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exam_problem_submits', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable();
			$table->integer('exam_problem_id')->nullable();
			$table->integer('exam_id')->nullable();
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
		Schema::drop('exam_problem_submits');
	}

}
