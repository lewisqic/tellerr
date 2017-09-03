<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_change_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_subscription_id')->unsigned()->nullable();
            $table->foreign('company_subscription_id')->references('id')->on('company_subscriptions');
            $table->integer('plan_privilege');
            $table->string('plan_name');
            $table->float('plan_price_month');
            $table->float('plan_price_year');
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
        Schema::dropIfExists('plan_change_requests');
    }
}
