<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users/current', '\App\Domains\UserManagement\Controllers\UserApiController@current');
Route::get('/users', '\App\Domains\UserManagement\Controllers\UserApiController@get');


// Session report lookups
Route::get('/activity-types','\App\Domains\SessionReports\Controllers\ActivityTypeApiController@get');
Route::get('/emotional-states','\App\Domains\SessionReports\Controllers\EmotionalStateApiController@get');
Route::get('/session-ratings','\App\Domains\SessionReports\Controllers\SessionRatingApiController@get');
Route::get('/safeguarding-options','\App\Domains\SessionReports\Controllers\SafeguardingConcernApiController@get');

// Session reports - v2
Route::get('/session-reports', '\App\Domains\SessionReports\Controllers\SessionReportApiController@get');
Route::get('/session-reports/export', '\App\Domains\SessionReports\Controllers\SessionReportApiController@export');
Route::get('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@getById');
Route::put('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@update');
Route::delete('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@delete');

// Expenses
Route::get('/expense-claims', '\App\Domains\Expenses\Controllers\ExpenseClaimApiController@get');

// Documents
Route::post('/documents/{id}/share','\App\Domains\Documents\Controllers\DocumentApiController@share');
Route::post('/documents/{id}/restore','\App\Domains\Documents\Controllers\DocumentApiController@restore');
Route::delete('/documents/{id}','\App\Domains\Documents\Controllers\DocumentApiController@delete');
Route::post('/documents','\App\Domains\Documents\Controllers\DocumentApiController@create');
Route::get('/documents/{id}/download','\App\Domains\Documents\Controllers\DocumentApiController@download');
Route::get('/documents/{id}','\App\Domains\Documents\Controllers\DocumentApiController@getById');
Route::get('/documents','\App\Domains\Documents\Controllers\DocumentApiController@get');

// Resource tagging
Route::get('/tags','\App\Domains\Tagging\Controllers\TagApiController@get');
Route::post('/tags','\App\Domains\Tagging\Controllers\TagApiController@create');
Route::delete('/tags/{id}','\App\Domains\Tagging\Controllers\TagApiController@delete');
Route::get('/tag-labels/associated', '\App\Domains\Tagging\Controllers\TagLabelApiController@getAssociated');
Route::get('/tagged-items', '\App\Domains\Tagging\Controllers\TaggedItemApiController@get');