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

    Route::group(['middleware' => 'role:Admin', 'namespace' => 'Admin'], function () {
        Route::get('/admin/dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
        Route::get('/admin/detailed-search', 'DetailedSearchController@detailedSearch')->name('admin.detailedSearch');
        Route::get('/admin/reporting', 'AdminController@reporting')->name('admin.reporting');

        Route::get('/admin/survivors', 'SurvivorController@survivors')->name('admin.survivors');
        Route::get('/admin/survivors/view/{id}', 'SurvivorController@viewSurvivor')->name('admin.survivors.view');
        Route::get('/admin/survivors/edit/{id?}', 'SurvivorController@editSurvivor')->name('admin.survivors.edit');
        Route::post('/admin/survivors/store', 'SurvivorController@storeSurvivor')->name('admin.survivors.store');
        Route::put('/admin/survivors/update/{id}', 'SurvivorController@updateSurvivor')->name('admin.survivors.update');
        Route::delete('/admin/survivors/{id}', 'SurvivorController@deleteSurvivor')->name('admin.survivors.delete');
        
        Route::get('/admin/ttus', 'TTUController@ttus')->name('admin.ttus');
        Route::get('/admin/ttus/view/{id?}', 'TTUController@ttusView')->name('admin.ttus.view');
        Route::get('/admin/ttus/edit/{id?}', 'TTUController@ttusEdit')->name('admin.ttus.edit');
        Route::post('/admin/ttus/store', 'TTUController@storeTTU')->name('admin.ttus.store');
        Route::put('/admin/ttus/update/{id}', 'TTUController@updateTTU')->name('admin.ttus.update');
        Route::delete('/admin/ttus/{id}', 'TTUController@deleteTTU')->name('admin.ttus.delete');
        Route::get('/admin/location-suggestions', 'TTUController@locationSuggestions')->name('admin.ttus.suggestions');
        Route::get('/admin/unit-suggestions', 'TTUController@unitSuggestions')->name('admin.ttus.unit_suggestions');
        Route::get('/admin/privatesite-by-ttu/{ttu_id}', 'TTUController@getPrivatesiteByTTU');

        Route::get('/admin/locations', 'LocationController@locations')->name('admin.locations');
        Route::get('/admin/locations/view/{id?}', 'LocationController@locationView')->name('admin.locations.view');
        Route::get('/admin/locations/edit/{id?}', 'LocationController@locationEdit')->name('admin.locations.edit');
        Route::put('/admin/locations/update/{id}', 'LocationController@locationUpdate')->name('admin.locations.update');
        Route::post('/admin/locations/store', 'LocationController@locationStore')->name('admin.locations.store');
        Route::delete('/admin/locations/delete/{id}', 'LocationController@deleteLocation')->name('admin.locations.delete');
        Route::post('/admin/rooms/update/{id}', [\App\Http\Controllers\Admin\LocationController::class, 'roomUpdate'])->name('admin.rooms.update');
        Route::post('/admin/lodge_units/update/{id}', [\App\Http\Controllers\Admin\LocationController::class, 'lodgeUnitUpdate'])->name('admin.lodge_units.update');

        Route::get('/admin/user_permissions', 'UserController@userPermissions')->name('admin.user_permissions');
        Route::post('/admin/user_permissions/add', 'UserController@addUser')->name('admin.user_permissions.add');
        Route::delete('/admin/user_permissions/{id}', 'UserController@removeUser')->name('admin.user_permissions.remove');
        Route::post('/admin/user_permissions/{id}/reactivate', 'UserController@reactivateUser')->name('admin.user_permissions.reactivate');
        Route::post('/admin/user_permissions/{id}/reset-password', 'UserController@resetPassword')->name('admin.user_permissions.reset_password');
    
        // For hotel rooms
        Route::get('/admin/rooms/create', 'LocationController@roomCreate')->name('admin.rooms.create');
        Route::post('/admin/rooms/store', 'LocationController@roomStore')->name('admin.rooms.store');
        Route::get('/admin/rooms/edit/{id?}', 'LocationController@roomEdit')->name('admin.rooms.edit');

        // For state park lodge units
        Route::get('/admin/lodge-units/create', 'LocationController@lodgeUnitCreate')->name('admin.lodge_units.create');
        Route::post('/admin/lodge-units/store', 'LocationController@lodgeUnitStore')->name('admin.lodge_units.store');
        Route::get('/admin/lodge-units/edit/{id?}', 'LocationController@lodgeUnitEdit')->name('admin.lodge_units.edit');

        Route::get('/admin/caseworkers', 'CaseworkerController@caseworkers')->name('admin.caseworkers');
        Route::post('/admin/caseworkers/store', 'CaseworkerController@store')->name('admin.caseworkers.store');
        Route::put('/admin/caseworkers/{id}', 'CaseworkerController@update')->name('admin.caseworkers.update');
        Route::delete('/admin/caseworkers/{id}', 'CaseworkerController@destroy')->name('admin.caseworkers.destroy');
    });

    Route::get('/admin/ttus/vin-autocomplete', function(\Illuminate\Http\Request $request) {
        $q = $request->query('query');
        return \App\TTU::whereNull('survivor_id')
            ->where('vin', 'like', '%' . $q . '%') // Match any part of VIN
            ->limit(10)
            ->get(['vin']);
    });
    
    Route::get('/admin/ttus/vin-details', function(\Illuminate\Http\Request $request) {
        $vin = $request->query('vin');
        $ttu = \App\TTU::where('vin', $vin)->whereNull('survivor_id')->first();
        if ($ttu) {
            return [
                'lo' => $ttu->lo,
                'lo_date' => $ttu->lo_date,
                'est_lo_date' => $ttu->est_lo_date,
                'vin' => $ttu->vin,
            ];
        }
        return response()->json(null, 404);
    });

    // Add this route for hotel name autocomplete
    Route::get('/admin/hotels/autocomplete', function(\Illuminate\Http\Request $request) {
        $q = $request->query('query', '');
        return \DB::table('hotel')
            ->where('name', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['name']);
    });

    Route::get('/admin/rooms/autocomplete', function(\Illuminate\Http\Request $request) {
        $hotel = $request->query('hotel', '');
        $hotelRow = \DB::table('hotel')->where('name', $hotel)->first();
        if (!$hotelRow) {
            return [];
        }
        return \DB::table('room')
            ->where('hotel_id', $hotelRow->id)
            ->whereNull('survivor_id')
            ->get(['room_num']);
    });

    Route::get('/admin/units/autocomplete', function(\Illuminate\Http\Request $request) {
        $park = $request->query('statepark', '');
        $parkRow = \DB::table('statepark')->where('name', $park)->first();
        if (!$parkRow) {
            return [];
        }
        return \DB::table('lodge_unit')
            ->where('statepark_id', $parkRow->id)
            ->whereNull('survivor_id')
            ->get(['unit_name']);
    });

    Route::get('/admin/stateparks/autocomplete', function(\Illuminate\Http\Request $request) {
        $q = $request->query('query', '');
        return \DB::table('statepark')
            ->where('name', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['name']);
    });

    Route::get('/admin/locations/autocomplete', function(\Illuminate\Http\Request $request) {
        $q = $request->query('query', '');
        return \DB::table('ttulocation')
            ->where('loc_name', 'like', '%' . $q . '%')
            ->distinct()
            ->limit(10)
            ->get(['loc_name as location']);
    });
    
    
});






