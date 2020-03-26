<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'ApiAuthController@login');
Route::post('/logout', 'ApiAuthController@logout');
Route::get('/auth-user', 'ApiAuthController@AuthUser');

Route::group(['middleware' => 'auth:api'], function () {
});

//The middleware auth:api is being used inside the controller on constructor, to ignore the store route
//A teenant can be created outside from the system
Route::resource('tenants', 'TenantController');