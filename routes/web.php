<?php

/**
 * subdomain routing for member areas
 */
Route::group(['domain' => '{account}.tellerr.{tld}'], function($group) {

    Route::get('/', ['uses' => 'Auth\IndexController@showLogin', 'middleware' => 'guest']);
    Route::get('f/{id}', function($id) {
        sd('Show FORM #' . $id);
    });


    // prevent www subdomain from matching all routes in this group
    foreach ( $group->getRoutes() as $route ) {
        $route->where('account', '^(?!.*www).*$');
    }
});

/**
 * Public site routes
 */
Route::group(['namespace' => 'Index'], function () {
    Route::get('/', ['uses' => 'IndexIndexController@showHome']);
    Route::get('pricing', ['uses' => 'IndexIndexController@showPricing']);
    Route::get('sign-up/{id}', ['uses' => 'IndexIndexController@showSignUp']);
    Route::post('validate-subdomain', ['uses' => 'IndexIndexController@handleValidateSubdomain']);
    Route::post('sign-up', ['uses' => 'IndexIndexController@handleSignUp']);
    Route::post('webhook/stripe', ['uses' => 'IndexIndexController@handleStripeWebhook']);
});


/**
 * Auth route group
 */
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    // GET
    Route::get('/', function() { return redirect('auth/login'); });
    Route::get('login', ['uses' => 'AuthIndexController@showLogin', 'middleware' => 'guest']);
    Route::get('logout', ['uses' => 'AuthIndexController@handleLogout']);
    Route::get('forgot', ['uses' => 'AuthIndexController@showForgot']);
    Route::get('reset/{code}', ['uses' => 'AuthIndexController@showReset']);
    // POST
    Route::post('login', ['uses' => 'AuthIndexController@handleLogin', 'middleware' => 'guest']);
    Route::post('forgot', ['uses' => 'AuthIndexController@handleForgot']);
    Route::post('reset', ['uses' => 'AuthIndexController@handleReset']);
});


/**
 * Admin route group
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checkPermissions:admin'], 'namespace' => 'Admin'], function() {

    // GET
    Route::get('/', ['uses' => 'AdminIndexController@showDashboard']);
    Route::post('remark-setting', ['uses' => 'AdminIndexController@saveRemarkSetting']);

    // profile
    Route::get('profile', ['uses' => 'AdminProfileController@index']);
    Route::put('profile', ['uses' => 'AdminProfileController@update']);

    // plans
    Route::get('plans/data', ['uses' => 'AdminPlanController@dataTables']);
    Route::patch('plans/{id}', ['uses' => 'AdminPlanController@restore']);
    Route::resource('plans', 'AdminPlanController');

    // administrators
    Route::get('administrators/data', ['uses' => 'AdminAdministratorController@dataTables']);
    Route::patch('administrators/{id}', ['uses' => 'AdminAdministratorController@restore']);
    Route::resource('administrators', 'AdminAdministratorController');

    // administrator roles
    Route::get('roles/data', ['uses' => 'AdminRoleController@dataTables']);
    Route::resource('roles', 'AdminRoleController');

    // settings
    Route::get('settings', ['uses' => 'AdminSettingController@index']);
    Route::post('settings', ['uses' => 'AdminSettingController@update']);

});


/**
 * Account route group
 */
Route::group(['prefix' => 'account', 'middleware' => ['auth:account', 'account'], 'namespace' => 'Account'], function() {

    // Dashboard
    Route::get('/', ['uses' => 'AccountIndexController@showDashboard']);
    Route::get('setup', ['uses' => 'AccountIndexController@showSetupWizard']);
    Route::post('setup', ['uses' => 'AccountIndexController@saveSetupData']);
    Route::get('activate', ['uses' => 'AccountIndexController@showActivate']);
    Route::post('activate', ['uses' => 'AccountIndexController@handleActivate']);
    Route::post('create-stripe-account', ['uses' => 'AccountIndexController@createStripeAccount']);
    Route::get('verify', ['uses' => 'AccountIndexController@showVerify']);
    Route::get('stripe-connect', ['uses' => 'AccountIndexController@connectStripeAccount']);
    Route::post('remark-setting', ['uses' => 'AccountIndexController@saveRemarkSetting']);

    // profile
    Route::get('profile', ['uses' => 'AccountProfileController@index']);
    Route::put('profile', ['uses' => 'AccountProfileController@update']);

    // settings
    Route::get('settings', ['uses' => 'AccountSettingController@index']);
    Route::put('settings', ['uses' => 'AccountSettingController@update']);

    // forms
    Route::get('forms/data', ['uses' => 'AccountFormController@dataTables']);
    Route::patch('forms/{id}', ['uses' => 'AccountFormController@restore']);
    Route::resource('forms', 'AccountFormController');

    // users
    Route::get('users/data', ['uses' => 'AccountUserController@dataTables']);
    Route::patch('users/{id}', ['uses' => 'AccountUserController@restore']);
    Route::resource('users', 'AccountUserController');

    // user roles
    Route::get('roles/data', ['uses' => 'AccountRoleController@dataTables']);
    Route::resource('roles', 'AccountRoleController');

    // billing
    Route::group(['prefix' => 'billing'], function() {
        Route::get('subscription', ['uses' => 'AccountBillingController@showSubscription']);
        Route::get('payment-methods', ['uses' => 'AccountBillingController@showPaymentMethods']);
        Route::post('payment-method', ['uses' => 'AccountBillingController@handleAddPaymentMethod']);
        Route::put('payment-method/{id}', ['uses' => 'AccountBillingController@handleSetDefaultPaymentMethod']);
        Route::delete('payment-method/{id}', ['uses' => 'AccountBillingController@handleDeletePaymentMethod']);
        Route::get('history', ['uses' => 'AccountBillingController@showBillingHistory']);
        Route::get('history/data', ['uses' => 'AccountBillingController@dataTables']);

        Route::get('change-plan', ['uses' => 'AccountBillingController@showChangePlan']);
        Route::get('upgrade', ['uses' => 'AccountBillingController@showUpgradeSubscription']);
        Route::post('upgrade', ['uses' => 'AccountBillingController@handleUpgradeSubscription']);
        Route::post('change-plan', ['uses' => 'AccountBillingController@handleChangePlan']);
        Route::post('cancel-plan-change', ['uses' => 'AccountBillingController@handleCancelPlanChange']);
        Route::post('change-installment', ['uses' => 'AccountBillingController@handleChangeInstallment']);
        Route::post('cancel-subscription', ['uses' => 'AccountBillingController@handleCancelSubscription']);
        Route::post('resume-subscription', ['uses' => 'AccountBillingController@handleResumeSubscription']);
    });

});


/*Route::get('mail', function() {

    $data = [
        'plan' => 'Standard',
        'amount' => \Format::currency(9.95),
        'installment' => 'month',
        'cancelation_date' => \Carbon::now()->toFormattedDateString(),
        'type' => 'upgrade'
    ];

    return new App\Mail\CancelationConfirmation($data);
});*/