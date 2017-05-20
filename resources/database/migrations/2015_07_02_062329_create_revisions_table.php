<?php

use Illuminate\Database\Migrations\Migration;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_versions', function ($table) {
            $table->increments('id');
            $table->morphs('versionable');
            $table->integer('user_id')->nullable();
            $table->longText('model_data');
            $table->string('reason', 100)->nullable();
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
        Schema::drop('content_versions');
    }
}
