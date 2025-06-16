<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailedSearchController extends Controller
{
    public function detailedSearch(Request $request)
    {
        $scope = $request->input('scope', 'all');
        $results = collect();
        $columns = [];

        if ($scope === 'survivors') {
            $results = DB::table('survivor')->get();
            // Dynamically get all columns
            $columns = array_keys((array)($results->first() ?? []));
        } elseif ($scope === 'ttus') {
            $results = DB::table('ttu')->get();
            $columns = array_keys((array)($results->first() ?? []));
        } elseif ($scope === 'locations') {
            $hotels = DB::table('hotel')->select('*', DB::raw("'Hotel' as location_type"))->get();
            $stateparks = DB::table('statepark')->select('*', DB::raw("'State Park' as location_type"))->get();
            $privatesites = DB::table('privatesite')->select('*', DB::raw("'Private Site' as location_type"))->get();
            $results = $hotels->merge($stateparks)->merge($privatesites);
            $columns = array_keys((array)($results->first() ?? []));
        } else { // all
            $columns = [
                'scope' => 'Scope',
                'name' => 'Name',
                'address' => 'Address',
                'phone' => 'Phone',
                'author' => 'Author',
            ];
            $survivors = DB::table('survivor')
                ->select(
                    DB::raw("CONCAT(fname, ' ', lname) as name"),
                    'address',
                    DB::raw("CONCAT_WS('\n', primary_phone, secondary_phone) as phone"),
                    DB::raw("'' as author")
                )->get()->map(function($row) {
                    $row->scope = 'Survivor';
                    return $row;
                });
            $ttus = DB::table('ttu')
                ->select(
                    DB::raw("'' as name"),
                    DB::raw("'' as address"),
                    DB::raw("'' as phone"),
                    'author'
                )->get()->map(function($row) {
                    $row->scope = 'TTU';
                    return $row;
                });
            $hotels = DB::table('hotel')->select('name', 'address', 'phone', 'author')->get()->map(function($row) {
                $row->scope = 'Hotel';
                return $row;
            });
            $stateparks = DB::table('statepark')->select('name', 'address', 'phone', 'author')->get()->map(function($row) {
                $row->scope = 'State Park';
                return $row;
            });
            $privatesites = DB::table('privatesite')->select('name', 'address', 'phone', 'author')->get()->map(function($row) {
                $row->scope = 'Private Site';
                return $row;
            });

            $results = $survivors->merge($ttus)->merge($hotels)->merge($stateparks)->merge($privatesites);
        }

        return view('admin.detailedsearch', compact('results', 'scope', 'columns'));
    }
}
