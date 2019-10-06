<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProblemDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problem_details', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('title')->nullable();
			$table->text('describe', 65535)->nullable();
			$table->text('input', 65535)->nullable();
			$table->text('output', 65535)->nullable();
			$table->text('sampleinput', 65535)->nullable();
			$table->text('sampleoutput', 65535)->nullable();
			$table->text('hint', 65535)->nullable();
			$table->string('source')->nullable();
			$table->integer('time')->nullable();
			$table->integer('memory')->nullable();
			$table->integer('other_time')->nullable();
			$table->integer('other_memory')->nullable();
			$table->string('language')->nullable();
			$table->integer('special_jusge')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('problem_details');
	}

}
