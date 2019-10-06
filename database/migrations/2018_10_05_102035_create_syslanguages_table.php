<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSyslanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('syslanguages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('language')->nullable();
			$table->string('compiler')->nullable();
			$table->string('oprion')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('syslanguages');
	}

}
