<?php

$router->group(['middleware' => 'web'], function ($router) {
    // Customer Support...
    $router->post('/support/email', 'SupportController@sendEmail');

    // Users...
    $router->get('/user/current', 'UserController@current');

    // Settings Dashboard...
    $router->get('/settings', 'Settings\DashboardController@show')->name('settings');

    // Profile Contact Information...
    $router->put('/settings/contact', 'Settings\Profile\ContactInformationController@update');

    // Profile Photo...
    $router->post('/settings/photo', 'Settings\Profile\PhotoController@store');

    // Security Settings...
    $router->put('/settings/password', 'Settings\Security\PasswordController@update');

    // Authentication...
    $router->get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    $router->post('/login', 'Auth\LoginController@login');
    $router->get('/logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset...
    $router->get('/password/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('password.reset');
    $router->post('/password/email', 'Auth\PasswordController@sendResetLinkEmail');
    $router->post('/password/reset', 'Auth\PasswordController@reset');
});