<?php

// Home Page
Route::get('/', 'WelcomeController@show');

// Override Registration Route
Route::get('/register','Auth\MyRegisterController@showRegistrationForm');
Route::post('/register','Auth\MyRegisterController@register');

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
Route::get('/my-expense-claims','HomeController@expense_claims');
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
Route::get('/manager/expense-claim/export','ManagerController@exportExpenseClaims');
Route::get('/manager/view-expense-claims','ManagerController@viewExpenseClaims');

// Mentee
Route::post('/mentee/restore/{id}','MenteeController@restore');
Route::resource('/mentee','MenteeController');

// Session reports
Route::get('/report/new','HomeController@newReport');
Route::get('/report/export','SessionReportController@export');
Route::resource('/report','SessionReportController');

// Expense claims
Route::get('/expense-claim/export','ExpenseClaimController@export');
Route::get('/receipt/download-all','ReceiptController@downloadAll');
Route::resource('/expense-claim','ExpenseClaimController');
Route::resource('/receipt','ReceiptController');

// BI Reporting Routes
Route::get('/reporting/mentor','MentorReportingController@generateIndexReport')->name('mentor-reporting-index');
Route::get('/reporting/mentor/export','MentorReportingController@generateExportableReport')->name('mentor-reporting-export');

// Schedule
Route::resource('/schedule','ScheduleController');

// Profile settings
Route::put('/settings/profile/details', 'ProfileDetailsController@update');
