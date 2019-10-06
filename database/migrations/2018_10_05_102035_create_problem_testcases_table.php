<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProblemTestcasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problem_testcases', function(Blueprint $table)
		{
			$table->integer('testcase_id', true);
			$table->integer('problem_id');
			$table->text('input', 65535)->nullable();
			$table->integer('score')->nullable();
			$table->text('output', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('problem_testcases');
	}

}
