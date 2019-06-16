<?php

// Home Page
Route::get('/', 'WelcomeController@show');

// Customer Support...
Route::post('/support/email', 'SupportController@sendEmail');

// Users...
Route::get('/user/current', 'UserController@current');

// Settings Dashboard...
Route::get('/settings', 'Settings\DashboardController@show')->name('settings');

// Profile Contact Information...
Route::put('/settings/contact', 'Settings\Profile\ContactInformationController@update');

// Profile Photo...
Route::post('/settings/photo', 'Settings\Profile\PhotoController@store');
Route::delete('/settings/photo', 'Settings\Profile\PhotoController@remove');

// Security Settings...
Route::put('/settings/password', 'Settings\Security\PasswordController@update');

// Authentication...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset...
Route::get('/password/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('password.reset');
Route::post('/password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Auth\PasswordController@reset');

// Registration Route
Route::get('/register','Auth\RegisterController@showRegistrationForm');
Route::post('/register','Auth\RegisterController@register');

/*
 * Role Management Routes
 */
Route::get('/roles/mentor','RoleController@mentor');
Route::get('/roles/manager','RoleController@manager');
Route::get('/roles/admin','RoleController@admin');
Route::post('/roles/manager','RoleController@store_manager');
Route::post('/roles/admin','RoleController@store_admin');
Route::post('/roles/assign-mentor','RoleController@assignMentor');
Route::post('/roles/assign-manager','RoleController@assignManager');
Route::delete('/roles/mentor/{mentor_id}/mentee/{mentee_id}','RoleController@disassociate_mentee');
Route::delete('/roles/mentor/{mentor_id}','RoleController@delete_mentor');
Route::delete('/roles/manager','RoleController@delete_manager');
Route::delete('/roles/admin','RoleController@delete_admin');

// Home
Route::get('/home', 'HomeController@show');
Route::delete('/delete-all','HomeController@deleteAll');
Route::get('/calendar','HomeController@calendar');

// Admin session form lookups
Route::post('/activity-type/restore/{id}','ActivityTypeController@restore');
Route::post('/physical-appearance/restore/{id}','PhysicalAppearanceController@restore');
Route::post('/emotional-state/restore/{id}','EmotionalStateController@restore');
Route::resource('/physical-appearance','PhysicalAppearanceController');
Route::resource('/emotional-state','EmotionalStateController');
Route::resource('/activity-type','ActivityTypeController');

// Finance
Route::get('/finance/expense-claim/export','FinanceController@exportExpenseClaims');
Route::get('/finance/process-expense-claims','FinanceController@processExpenseClaims');

// Manager
Route::get('/manager','ManagerController@index');

// Mentee
Route::post('/mentee/restore/{id}','MenteeController@restore');
Route::resource('/mentee','MenteeController');

// Session reports
Route::get('/report/new','HomeController@newReport');
Route::get('/report/export','SessionReportController@export')->name('report.export');
Route::resource('/report','SessionReportController');

// Expense claims
Route::get('/expense-claim/export','ExpenseClaimController@export')->name('expense-claim.export');
Route::get('/receipt/download-all','ReceiptController@downloadAll')->name('receipt.download-all');
Route::get('/expense-claim/new','ExpenseClaimController@newExpenseClaim');
Route::resource('/expense-claim','ExpenseClaimController');
Route::resource('/receipt','ReceiptController');

// BI Reporting Routes
Route::get('/reporting/mentor','MentorReportingController@generateIndexReport')->name('mentor-reporting-index');
Route::get('/reporting/mentor/export','MentorReportingController@generateExportableReport')->name('mentor-reporting-export');

// Schedule
Route::resource('/schedule','ScheduleController');

// Old routes to deprecate eventually once people's symlinks are updated
Route::redirect('/my-reports', '/report/new');
Route::redirect('/own-reports', '/report');
Route::redirect('/my-expense-claims', '/expense-claim/new');
Route::redirect('/manager/expense-claim/export', '/expense-claim/export');
Route::redirect('/manager/view-expense-claims', '/expense-claim');
