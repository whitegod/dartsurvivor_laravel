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
        $keyword = $request->input('keyword');
        $results = collect();

        // Define the columns to show in all cases
        $detailedSearchColumns = [
            ['key' => 'index', 'label' => 'No.'],
            ['key' => 'scope', 'label' => 'Scope'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'address', 'label' => 'Address'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'author', 'label' => 'Author'],
        ];

        if ($scope === 'survivors') {
            $query = DB::table('survivor')
                ->select(
                    DB::raw("'Survivor' as scope"),
                    DB::raw("CONCAT(fname, ' ', lname) as name"),
                    'address',
                    DB::raw("CONCAT_WS('\n', primary_phone, secondary_phone) as phone"),
                    DB::raw("'' as author")
                );
            if ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(fname, ' ', lname)"), 'like', "%$keyword%")
                      ->orWhere('address', 'like', "%$keyword%")
                      ->orWhere('primary_phone', 'like', "%$keyword%")
                      ->orWhere('secondary_phone', 'like', "%$keyword%");
                });
            }
            $results = $query->get();
        } elseif ($scope === 'ttus') {
            $query = DB::table('ttu')
                ->select(
                    DB::raw("'TTU' as scope"),
                    DB::raw("'' as name"),
                    DB::raw("'' as address"),
                    DB::raw("'' as phone"),
                    'author'
                );
            if ($keyword) {
                $query->where('author', 'like', "%$keyword%");
            }
            $results = $query->get();
        } elseif ($scope === 'locations') {
            $locations = collect()
                ->merge(DB::table('hotel')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('statepark')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('privatesite')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get());
            if ($keyword) {
                $locations = $locations->filter(function($row) use ($keyword) {
                    return (stripos($row->name, $keyword) !== false)
                        || (stripos($row->address, $keyword) !== false)
                        || (stripos($row->phone, $keyword) !== false)
                        || (stripos($row->author, $keyword) !== false);
                });
            }
            $results = $locations->values();
        } else { // all
            $survivors = DB::table('survivor')
                ->select(
                    DB::raw("'Survivor' as scope"),
                    DB::raw("CONCAT(fname, ' ', lname) as name"),
                    'address',
                    DB::raw("CONCAT_WS('\n', primary_phone, secondary_phone) as phone"),
                    DB::raw("'' as author")
                );
            $ttus = DB::table('ttu')
                ->select(
                    DB::raw("'TTU' as scope"),
                    DB::raw("'' as name"),
                    DB::raw("'' as address"),
                    DB::raw("'' as phone"),
                    'author'
                );
            $locations = collect()
                ->merge(DB::table('hotel')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('statepark')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('privatesite')->select(
                    DB::raw("'Location' as scope"),
                    'name', 'address', 'phone', 'author'
                )->get());

            if ($keyword) {
                $survivors->where(function($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(fname, ' ', lname)"), 'like', "%$keyword%")
                      ->orWhere('address', 'like', "%$keyword%")
                      ->orWhere('primary_phone', 'like', "%$keyword%")
                      ->orWhere('secondary_phone', 'like', "%$keyword%");
                });
                $ttus->where('author', 'like', "%$keyword%");
                $locations = $locations->filter(function($row) use ($keyword) {
                    return (stripos($row->name, $keyword) !== false)
                        || (stripos($row->address, $keyword) !== false)
                        || (stripos($row->phone, $keyword) !== false)
                        || (stripos($row->author, $keyword) !== false);
                });
            }

            $results = $survivors->get()->merge($ttus->get())->merge($locations->values());
        }

        return view('admin.detailedSearch', [
            'results' => $results,
            // 'detailedSearchColumns' => $detailedSearchColumns,
            'columns' => $detailedSearchColumns, // add this line
            'scope' => $scope,
            'keyword' => $keyword,
        ]);
    }
}
