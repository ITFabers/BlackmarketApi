<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sliders', function($table) {
          $table->string('description')->nullable();
          $table->string('btn_text1')->nullable();
          $table->string('btn_text2')->nullable();
          $table->string('btn_url1')->nullable();
          $table->string('btn_url2')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
