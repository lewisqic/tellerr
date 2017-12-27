<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('company_id')->unsigned()->nullable();
            $t->foreign('company_id')->references('id')->on('companies');
            $t->string('name');
            $t->boolean('show_title');
            $t->boolean('show_logo');
            $t->string('logo_image');
            $t->string('background_type');
            $t->string('background_image');
            $t->string('background_color');
            $t->string('primary_color');
            $t->string('secondary_color');
            $t->text('custom_css');
            $t->timestamps();
            $t->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
}
