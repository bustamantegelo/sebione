<?php
/** Users route */
Route::prefix('users')->group(function () {
    Route::get('/', 'UsersApiController@index');
});
