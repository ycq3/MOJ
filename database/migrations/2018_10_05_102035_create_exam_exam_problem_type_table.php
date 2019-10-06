<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExamExamProblemTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exam_exam_problem_type', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('exam_id')->nullable();
			$table->integer('exam_problem_type_id')->nullable();
			$table->integer('score')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exam_exam_problem_type');
	}

}
