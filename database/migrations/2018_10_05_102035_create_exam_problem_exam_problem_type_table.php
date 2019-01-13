<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExamProblemExamProblemTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exam_problem_exam_problem_type', function(Blueprint $table)
		{
			$table->integer('exam_problem_type_id');
			$table->integer('exam_problem_id');
			$table->primary(['exam_problem_type_id','exam_problem_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exam_problem_exam_problem_type');
	}

}
