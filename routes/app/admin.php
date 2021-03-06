<?php
/** View Route */
Route::get('/', function() {
    return view('main');
});

/** Users route */
Route::prefix('users')->group(function () {
    Route::post('/login', 'UserAdminController@login');
});

/** Companies route */
Route::resource('/companies', 'CompaniesAdminController');
Route::prefix('companies')->group(function () {
    Route::post('/{iCompanyId}', 'CompaniesAdminController@update');
});

/** Employees route */
Route::resource('/employees', 'EmployeesAdminController');
