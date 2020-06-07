<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/app/{vue_capture?}', function () {
    return view('vue.index');
})->where('vue_capture', '.*');

// Home Page
Route::get('/', 'WelcomeController@show');

// Customer Support...
Route::post('/support/email', 'SupportController@sendEmail');

// Users...
Route::delete('/user/{user_id}','UserController@delete');
Route::post('/user/{user_id}/restore','UserController@restore');

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
Route::post('/roles/manager','RoleController@store_manager_role');
Route::post('/roles/admin','RoleController@store_admin_role');
Route::post('/roles/assign-mentor','RoleController@assignMentor');
Route::post('/roles/assign-manager','RoleController@assignManager');
Route::delete('/roles/mentor/{mentor_id}/mentee/{mentee_id}','RoleController@disassociate_mentee');
Route::delete('/roles/manager','RoleController@delete_manager_role');
Route::delete('/roles/admin','RoleController@delete_admin_role');

// Home
Route::get('/home', 'HomeController@show');

// Finance
Route::get('/finance/expense-claim/export','FinanceController@exportExpenseClaims');
Route::get('/finance/process-expense-claims','FinanceController@processExpenseClaims');

// Mentee
Route::post('/mentee/restore/{id}','MenteeController@restore');
Route::resource('/mentee','MenteeController');

// Session reports - lookups
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

// Expense claims
Route::get('/expense-claim/export','ExpenseClaimController@export')->name('expense-claim.export');
Route::get('/receipt/download-all','ReceiptController@downloadAll')->name('receipt.download-all');
Route::get('/expense-claim/new','ExpenseClaimController@newExpenseClaim');
Route::resource('/expense-claim','ExpenseClaimController');
Route::resource('/receipt','ReceiptController');

// BI Reporting Routes
Route::get('/reporting/mentor','MentorReportingController@generateIndexReport')->name('mentor-reporting-index');
Route::get('/reporting/mentor/export','MentorReportingController@generateExportableReport')->name('mentor-reporting-export');

// Calendar and events
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

// Documents
Route::get('/documents/upload/index','\App\Domains\Documents\Controllers\DocumentController@uploadIndex');
Route::get('/documents/index','\App\Domains\Documents\Controllers\DocumentController@index');

Route::post('/documents/{id}/share','\App\Domains\Documents\Controllers\DocumentController@share');
Route::post('/documents/{id}/restore','\App\Domains\Documents\Controllers\DocumentController@restore');
Route::delete('/documents/{id}','\App\Domains\Documents\Controllers\DocumentController@delete');
Route::post('/documents','\App\Domains\Documents\Controllers\DocumentController@store');
Route::get('/documents/{id}/download','\App\Domains\Documents\Controllers\DocumentController@download');
Route::get('/documents/{id}','\App\Domains\Documents\Controllers\DocumentController@getOne');
Route::get('/documents','\App\Domains\Documents\Controllers\DocumentController@getAll');

// Resource Tagging
Route::get('/tags','\App\Domains\Tagging\Controllers\TagController@getTags');
Route::post('/tags','\App\Domains\Tagging\Controllers\TagController@createTags');
Route::delete('/tags/{id}','\App\Domains\Tagging\Controllers\TagController@deleteTag');

Route::get('/tag-labels/associated', '\App\Domains\Tagging\Controllers\TagLabelController@getAssociatedTagLabels');

Route::get('/tagged-items', '\App\Domains\Tagging\Controllers\TaggedItemController@getTaggedItems');

// Funding
Route::get('/fundings/export','\App\Domains\Funding\Controllers\FundingController@export')->name('fundings.export');
Route::post('/funders/{id}/restore','\App\Domains\Funding\Controllers\FunderController@restore');
Route::resource('/fundings','\App\Domains\Funding\Controllers\FundingController');
Route::resource('/funders','\App\Domains\Funding\Controllers\FunderController');
