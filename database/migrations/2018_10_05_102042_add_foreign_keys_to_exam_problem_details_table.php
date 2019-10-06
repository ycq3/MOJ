<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToExamProblemDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('exam_problem_details', function(Blueprint $table)
		{
			$table->foreign('id', 'exam_problem_details_ibfk_1')->references('id')->on('problems')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('exam_problem_details', function(Blueprint $table)
		{
			$table->dropForeign('exam_problem_details_ibfk_1');
		});
	}

}
