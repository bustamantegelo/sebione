<?php
/** View Route */
Route::get('/', function() {
    return view('main');
});

/** Users route */
Route::prefix('users')->group(function () {
    Route::post('/login', 'UserAdminController@login');
});
