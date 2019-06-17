<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPerformanceIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('performance_indicators');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('monthly_recurring_revenue');
            $table->decimal('yearly_recurring_revenue');
            $table->decimal('daily_volume');
            $table->integer('new_users');
            $table->timestamps();

            $table->index('created_at');
        });
    }
}
