<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset');
Route::match(['get', 'post'], 'register', function(){
    return redirect('/home');
});
 
Auth::routes();

Route::get('/', function () {
     return redirect('/home');
 });

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
 

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index');

    Route::group(['middleware' => 'role:Admin|Worker|Contact|Payroll|Account'], function (){

    });

    Route::group(['middleware' => 'role:Admin', 'namespace' => 'Admin'], function (){
        Route::get('/admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
        Route::get('/admin/detailed-search', 'AdminController@detailedSearch')->name('admin.detailedSearch');
        Route::get('/admin/reporting', 'AdminController@reporting')->name('admin.reporting');

        Route::get('/admin/survivors', 'AdminController@survivors')->name('admin.survivors');
        Route::get('/admin/ttus', 'AdminController@ttus')->name('admin.ttus');
        Route::get('/admin/ttus/{id}', 'AdminController@ttusEdit')->name('admin.ttus.edit');
        Route::post('/admin/ttus/store', 'AdminController@storeTTU')->name('admin.ttus.store');
        Route::delete('/admin/ttus/{id}', 'AdminController@deleteTTU')->name('admin.ttus.delete');

        Route::get('/admin/user_permissions', 'AdminController@userPermissions')->name('admin.user_permissions');
    });

});





