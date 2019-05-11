<?php

// Home Page
Route::get('/', 'WelcomeController@show');

// Override Registration Route
Route::get('/register','Auth\MyRegisterController@showRegistrationForm');
Route::post('/register','Auth\MyRegisterController@register');

// Dashboard (different views for different roles)
Route::get('/home', 'HomeController@show');

// Show the Session Report Form
Route::get('/my-reports','HomeController@reports');

// Show the Calendar
Route::get('/calendar','HomeController@calendar');

// Show the Expense Claim Form
Route::get('/my-expense-claims','HomeController@expense_claims');

// Manager Dashboard
Route::get('/manager','ManagerController@index');

// List all the claims that the manager can approve
Route::get('/manager/review-claims','ManagerController@reviewClaims');

// List all the approved claims so finance can process it
Route::get('/finance/review-claims','FinanceController@reviewClaims');

// Process forms that restore soft deleted items
// Mentees, Activity Type, Physical Appearances and Emotional State can be soft deleted
Route::post('/mentee/restore/{id}','MenteeController@restore');
Route::post('/activity-type/restore/{id}','ActivityTypeController@restore');
Route::post('/physical-appearance/restore/{id}','PhysicalAppearanceController@restore');
Route::post('/emotional-state/restore/{id}','EmotionalStateController@restore');

// Export Report and Expense Claim data
Route::get('/report/export','SessionReportController@export');
Route::get('/expense-claim/export','ExpenseClaimController@export');
Route::get('/manager/report','ManagerController@reviewReports');
Route::get('/manager/report/export','ManagerController@exportReports');
Route::get('/manager/expense-claim/export','ManagerController@exportExpenseClaims');
Route::get('/finance/expense-claim/export','FinanceController@exportExpenseClaims');


// Download all Receipts
Route::get('/receipt/download-all','ReceiptController@downloadAll');

/*
 * Role Management Routes
 */
// Display the Different Roles and Links to manage them
Route::get('/roles','RoleController@index');

// Options to manage mentors. Allows admins to assign mentors to mentee
Route::get('/roles/mentor','RoleController@mentor');

// Options to manage managers. Allows admins to promote users to managers, demote users, assign managers to mentors
Route::get('/roles/manager','RoleController@manager');

// Options to manage finance. Allows admins to promote users to finance / demote users.
Route::get('/roles/finance','RoleController@finance');

// Options to manage admin. Allows admins to promote users to admin / demote users.
Route::get('/roles/admin','RoleController@admin');

Route::get('/own-reports','SessionReportController@ownReports');

// Process a request to promote to manager
Route::post('/roles/manager','RoleController@store_manager');

// Process a request to promote to finance
Route::post('/roles/finance','RoleController@store_finance');

// Process a request to promote to admin
Route::post('/roles/admin','RoleController@store_admin');

// Process a request to Assign a Mentor to a Mentee
Route::post('/roles/assign-mentor','RoleController@assignMentor');

// Process a request to Assign a Manager to a Mentor
Route::post('/roles/assign-manager','RoleController@assignManager');

// Disassociate a Mentor and Mentee Pair
Route::delete('/roles/mentor/{mentor_id}/mentee/{mentee_id}','RoleController@disassociate_mentee');

// Disassociate a Mentor and Mentee Pair
Route::delete('/roles/mentor/{mentor_id}','RoleController@delete_mentor');

// Demote Manager. Disassociate all Mentees associated with the Manager.
Route::delete('/roles/manager','RoleController@delete_manager');

// Demote from Finance role.
Route::delete('/roles/finance','RoleController@delete_finance');

// Demote from Admin role.
Route::delete('/roles/admin','RoleController@delete_admin');

// Delete all Reports and Expense Claims
Route::delete('/delete-all','HomeController@deleteAll');

// BI Reporting Routes
Route::get('/reporting/mentor','MentorReportingController@index');
Route::get('/reporting/mentor/generate','MentorReportingController@generateIndexReport')->name('mentor-reporting-index');
Route::get('/reporting/mentor/export','MentorReportingController@generateExportableReport')->name('mentor-reporting-export');


/*
 * Resource Routes
 */
Route::resource('/report','SessionReportController');
Route::resource('/schedule','ScheduleController');
Route::resource('/expense-claim','ExpenseClaimController');
Route::resource('/mentee','MenteeController');
Route::resource('/physical-appearance','PhysicalAppearanceController');
Route::resource('/emotional-state','EmotionalStateController');
Route::resource('/activity-type','ActivityTypeController');
Route::resource('/receipt','ReceiptController');

Route::put('/settings/profile/details', 'ProfileDetailsController@update');
