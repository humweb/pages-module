<?php

use Humweb\Pages\Models\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($table)
		{
			$table->increments('id');
			$table->integer('parent_id')->index();
			$table->string('uri')->index();
			$table->string('title');
			$table->string('slug')->index();
			$table->string('layout')->nullable();
			$table->text('content');
			$table->boolean('published');
			$table->integer('created_by');
			$table->datetime('published_at')->nullable();
			$table->timestamps();
			$table->text('css')->nullable();
			$table->text('js')->nullable();
			$table->string('meta_title')->nullable();
			$table->string('meta_description')->nullable();
			$table->enum('meta_robots', array('noindex','nofollow','both','index','all'))->default('all');
			$table->boolean('is_index')->default(0);
			$table->boolean('comment_status')->default(false);
			$table->integer('position');

			$table->index('created_at');

		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
