<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('company_id')->unsigned()->nullable();
            $t->foreign('company_id')->references('id')->on('companies');
            $t->string('title');
            $t->boolean('show_description');
            $t->text('description');
            $t->boolean('show_terms');
            $t->text('terms');
            $t->boolean('disable');
            $t->integer('submission_limit');
            $t->date('disable_date');
            $t->boolean('enable_coupons');
            $t->boolean('suggested');
            $t->text('amount');
            $t->text('amount_description');
            $t->boolean('charge_fee');
            $t->string('fee_name');
            $t->string('fee_type');
            $t->float('fee_amount');
            $t->integer('fee_percentage');
            $t->boolean('upfront');
            $t->float('upfront_amount');
            $t->text('upfront_description');
            $t->string('upfront_charge_start');
            $t->string('frequency_type');
            $t->string('recurring_options');
            $t->integer('recurring_period_value');
            $t->string('recurring_period_term');
            $t->string('recurring_start');
            $t->integer('recurring_start_after_trial_value');
            $t->string('recurring_start_after_trial_term');
            $t->date('recurring_start_fixed_date');
            $t->integer('recurring_start_day_month');
            $t->string('duration');
            $t->integer('duration_fixed_periods');
            $t->string('default_frequency');
            $t->boolean('additional_fields');
            $t->text('additional_fields_title');
            $t->text('additional_fields_description');
            $t->text('additional_fields_type');
            $t->text('additional_fields_make_required');
            $t->text('additional_fields_options');
            $t->string('after_submission');
            $t->text('after_submission_message');
            $t->string('after_submission_url');
            $t->string('email_message');
            $t->string('theme');
            $t->string('layout');
            $t->text('components_single');
            $t->text('components_double');
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
        Schema::dropIfExists('forms');
    }
}
