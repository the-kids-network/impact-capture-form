<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagged_items', function (Blueprint $table) {
            $table->bigIncrements('id');      
            $table->unsignedBigInteger('resource_id');
            $table->string('resource_type', 25);
            $table->timestamps();

            $table->unique(['resource_id', 'resource_type']);

            $table->index('resource_id');
            $table->index('resource_type');
            $table->index(['resource_id', 'resource_type']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');      
            $table->unsignedBigInteger('tagged_item_id');
            $table->string('label', 25);
            $table->timestamps();

            $table->index('tagged_item_id');
            $table->index('label');
            $table->index(['tagged_item_id', 'label']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tagged_items');
    }
}
