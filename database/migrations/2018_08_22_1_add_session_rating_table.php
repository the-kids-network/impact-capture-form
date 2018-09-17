<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->boolean('selectable')->default(true);
        });

        // rating_id needs to start out as nullable until we populate the new column
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('rating_id')->nullable();
        });

        $this->populateValues();

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedInteger('rating_id')->nullable(false)->change();
            $table->foreign('rating_id')->references('id')->on('session_ratings');
        });
    }

    private function populateValues()
    {
        DB::table('session_ratings')->insert(['value' => 'Unknown', 'selectable' => false]);

        $ratings = ['Poor', 'Average', 'Good', 'Very Good', 'Excellent'];
        foreach ($ratings as $rating) {
            DB::table('session_ratings')->insert(['value' => $rating]);
        }

        DB::table('reports')->update(['rating_id' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_rating_id_foreign');
            $table->dropColumn('rating_id');
        });
        Schema::drop('session_ratings');
    }
}
