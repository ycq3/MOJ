<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExamProblemUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exam_problem_users', function(Blueprint $table)
		{
			$table->integer('exam_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('exam_problem_type_id')->nullable();
			$table->integer('exam_problem_id')->nullable();
			$table->integer('score')->nullable();
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
		Schema::drop('exam_problem_users');
	}

}
