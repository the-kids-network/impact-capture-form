<?php

use Illuminate\Http\Request;
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

Route::get('/user/current', 'UserApiController@current');

// Session report lookups
Route::get('/activity-types','\App\Domains\SessionReports\Controllers\ActivityTypeApiController@get');
Route::get('/emotional-states','\App\Domains\SessionReports\Controllers\EmotionalStateApiController@get');
Route::get('/session-ratings','\App\Domains\SessionReports\Controllers\SessionRatingApiController@get');
Route::get('/safeguarding-options','\App\Domains\SessionReports\Controllers\SafeguardingConcernApiController@get');

// Session reports - v2
Route::get('/session-reports', '\App\Domains\SessionReports\Controllers\SessionReportApiController@get');
Route::get('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@getById');
Route::put('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@update');
Route::delete('/session-reports/{id}', '\App\Domains\SessionReports\Controllers\SessionReportApiController@delete');