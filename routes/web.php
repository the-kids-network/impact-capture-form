<?php

use Illuminate\Support\Facades\Route;

Route::get('/app/{vue_capture?}', 'VueController@index')->where('vue_capture', '.*');

// Welcome
Route::get('/', 'WelcomeController@show');

// Home
Route::get('/home', 'HomeController@show');

// Customer Support...
Route::post('/support/email', 'SupportController@sendEmail');

/**
 * User management
 */
// Registration 
Route::get('/register','\App\Domains\UserManagement\Controllers\RegisterController@showRegistrationForm');
Route::post('/register','\App\Domains\UserManagement\Controllers\RegisterController@register');
// Authentication
Route::get('/login', '\App\Domains\UserManagement\Controllers\LoginController@showLoginForm')->name('login');
Route::post('/login', '\App\Domains\UserManagement\Controllers\LoginController@login');
Route::get('/logout', '\App\Domains\UserManagement\Controllers\LoginController@logout')->name('logout');
// Password reset flow
Route::get('/password/reset/{token?}', '\App\Domains\UserManagement\Controllers\PasswordResetController@showResetForm')->name('password.reset');
Route::post('/password/reset/email', '\App\Domains\UserManagement\Controllers\PasswordResetController@sendResetLinkEmail');
Route::post('/password/reset', '\App\Domains\UserManagement\Controllers\PasswordResetController@reset');

// User profile
Route::put('/users/{user_id}/password', '\App\Domains\UserManagement\Controllers\UserPasswordController@update');
Route::put('/users/{user_id}/contact', '\App\Domains\UserManagement\Controllers\UserContactInformationController@update');
Route::post('/users/{user_id}/photo', '\App\Domains\UserManagement\Controllers\UserPhotoController@store');
Route::delete('/users/{user_id}/photo', '\App\Domains\UserManagement\Controllers\UserPhotoController@remove');
Route::delete('/users/{user_id}','\App\Domains\UserManagement\Controllers\UserController@delete');
Route::post('/users/{user_id}/restore','\App\Domains\UserManagement\Controllers\UserController@restore');

// User roles
Route::put('/users/{id}/roles/{role}','\App\Domains\UserManagement\Controllers\UserRoleController@setRole');
Route::delete('/users/{id}/roles/{role}','\App\Domains\UserManagement\Controllers\UserRoleController@removeRole');

// User relationships
Route::put('/users/{manager_id}/mentors/{mentor_id}','\App\Domains\UserManagement\Controllers\UserRelationshipController@assignMentorToManager');
Route::put('/users/{mentor_id}/mentees/{mentee_id}','\App\Domains\UserManagement\Controllers\UserRelationshipController@assignMenteeToMentor');
Route::delete('/users/{mentor_id}/mentees/{mentee_id}','\App\Domains\UserManagement\Controllers\UserRelationshipController@unassignMenteeFromMentor');

// Mentee
Route::post('/mentees/{id}/restore','\App\Domains\UserManagement\Controllers\MenteeController@restore');
Route::resource('/mentees','\App\Domains\UserManagement\Controllers\MenteeController');

// User management landing pages
Route::get('/user-management/mentors','\App\Domains\UserManagement\Controllers\UserManagementPageController@mentor');
Route::get('/user-management/managers','\App\Domains\UserManagement\Controllers\UserManagementPageController@manager');
Route::get('/user-management/admins','\App\Domains\UserManagement\Controllers\UserManagementPageController@admin');

/**
 * Settings dashboard
 */
Route::get('/settings', 'Settings\DashboardController@show')->name('settings');

/**
 * Session reports
 */
// Lookups
Route::get('/activity-types', '\App\Domains\SessionReports\Controllers\ActivityTypeController@index');
Route::post('/activity-types', '\App\Domains\SessionReports\Controllers\ActivityTypeController@create');
Route::delete('/activity-types/{id}', '\App\Domains\SessionReports\Controllers\ActivityTypeController@delete');
Route::post('/activity-types/{id}/restore/','\App\Domains\SessionReports\Controllers\ActivityTypeController@restore');

Route::get('/emotional-states', '\App\Domains\SessionReports\Controllers\EmotionalStateController@index');
Route::post('/emotional-states', '\App\Domains\SessionReports\Controllers\EmotionalStateController@create');
Route::delete('/emotional-states/{id}', '\App\Domains\SessionReports\Controllers\EmotionalStateController@delete');
Route::post('/emotional-states/{id}/restore/','\App\Domains\SessionReports\Controllers\EmotionalStateController@restore');
// Session reports - v1
Route::get('/report/new','\App\Domains\SessionReports\Controllers\SessionReportController@newReportForm');
Route::get('/report/{id}/edit','\App\Domains\SessionReports\Controllers\SessionReportController@editReportForm');
Route::get('/report/export','\App\Domains\SessionReports\Controllers\SessionReportController@export')->name('report.export');
Route::get('/report', '\App\Domains\SessionReports\Controllers\SessionReportController@get')->name('reports.get');
Route::get('/report/{id}', '\App\Domains\SessionReports\Controllers\SessionReportController@getById');
Route::post('/report', '\App\Domains\SessionReports\Controllers\SessionReportController@create');
Route::delete('/report/{id}', '\App\Domains\SessionReports\Controllers\SessionReportController@delete');
// session reports v2
// redirect to vue routed app
Route::redirect('/session-reports', '/app#/session-reports');

/**
 * Expense Claims
 */
Route::get('/expense-claim/export','\App\Domains\Expenses\Controllers\ExpenseClaimController@export')->name('expense-claim.export');
Route::get('/expense-claim/new','\App\Domains\Expenses\Controllers\ExpenseClaimController@newExpenseClaim');
Route::resource('/expense-claim','\App\Domains\Expenses\Controllers\ExpenseClaimController');

Route::get('/receipts/download-all','\App\Domains\Expenses\Controllers\ReceiptController@get')->name('receipts.download-all');
Route::get('/receipts/{id}','\App\Domains\Expenses\Controllers\ReceiptController@getById');

Route::get('/finance/expense-claim/export','\App\Domains\Expenses\Controllers\FinanceController@exportExpenseClaims');
Route::get('/finance/process-expense-claims','\App\Domains\Expenses\Controllers\FinanceController@processExpenseClaims');

// expenses v2
// redirect to vue routed app
Route::redirect('/expenses', '/app#/expenses');


/**
 * BI Reporting Routes
 */
Route::get('/reporting/mentor','MentorReportingController@generateIndexReport')->name('mentor-reporting-index');
Route::get('/reporting/mentor/export','MentorReportingController@generateExportableReport')->name('mentor-reporting-export');

/**
 * Calendar and events  
 */ 
Route::get('/mentee/leave/new','\App\Domains\Calendar\Controllers\MenteeLeaveController@newLeave');
Route::get('/mentee/leave/{id}','\App\Domains\Calendar\Controllers\MenteeLeaveController@getOne');
Route::post('/mentee/leave','\App\Domains\Calendar\Controllers\MenteeLeaveController@create');
Route::put('/mentee/leave/{id}','\App\Domains\Calendar\Controllers\MenteeLeaveController@update');
Route::delete('/mentee/leave/{id}','\App\Domains\Calendar\Controllers\MenteeLeaveController@delete');

Route::get('/mentor/leave/new','\App\Domains\Calendar\Controllers\MentorLeaveController@newLeave');
Route::get('/mentor/leave/{id}','\App\Domains\Calendar\Controllers\MentorLeaveController@getOne');
Route::post('/mentor/leave','\App\Domains\Calendar\Controllers\MentorLeaveController@create');
Route::put('/mentor/leave/{id}','\App\Domains\Calendar\Controllers\MentorLeaveController@update');
Route::delete('/mentor/leave/{id}','\App\Domains\Calendar\Controllers\MentorLeaveController@delete');

Route::get('/planned-session/new','\App\Domains\Calendar\Controllers\PlannedSessionController@newPlannedSession');
Route::get('/planned-session/next','\App\Domains\Calendar\Controllers\PlannedSessionController@getNext');
Route::get('/planned-session/{id}','\App\Domains\Calendar\Controllers\PlannedSessionController@getOne');
Route::post('/planned-session','\App\Domains\Calendar\Controllers\PlannedSessionController@create');
Route::put('/planned-session/{id}','\App\Domains\Calendar\Controllers\PlannedSessionController@update');
Route::delete('/planned-session/{id}','\App\Domains\Calendar\Controllers\PlannedSessionController@delete');

Route::resource('/calendar','\App\Domains\Calendar\Controllers\CalendarController');

/**
 * Documents 
 */
Route::get('/documents/upload/index','\App\Domains\Documents\Controllers\DocumentController@uploadIndex');
Route::get('/documents/index','\App\Domains\Documents\Controllers\DocumentController@index');

/**
 * Resource Tagging
 */


/**
 * Funding
 */ 
Route::get('/fundings/export','\App\Domains\Funding\Controllers\FundingController@export')->name('fundings.export');
Route::post('/funders/{id}/restore','\App\Domains\Funding\Controllers\FunderController@restore');
Route::resource('/fundings','\App\Domains\Funding\Controllers\FundingController');
Route::resource('/funders','\App\Domains\Funding\Controllers\FunderController');
