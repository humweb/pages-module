<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageRelatedTable extends Migration
{
    public function up()
    {
        Schema::create('content_related', function (Blueprint $table) {
            $table->string('source_type');
            $table->unsignedInteger('source_id');
            $table->string('related_type');
            $table->unsignedInteger('related_id');
            $table->unique(
                ['source_id', 'source_type', 'related_id', 'related_type'],
                'relatables_unique'
            );
        });
    }

    public function down()
    {
        Schema::drop('content_related');
    }
}
