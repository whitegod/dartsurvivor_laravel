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
        $countBy = $request->input('count_by');
        $results = collect();

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
                    'id',
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
                ->leftJoin('hotel', function($join) {
                    $join->on('ttu.location', '=', 'hotel.name')
                         ->where('ttu.location_type', '=', 'hotel');
                })
                ->leftJoin('statepark', function($join) {
                    $join->on('ttu.location', '=', 'statepark.name')
                         ->where('ttu.location_type', '=', 'statepark');
                })
                ->leftJoin('privatesite', function($join) {
                    $join->on('ttu.location', '=', 'privatesite.name')
                         ->where('ttu.location_type', '=', 'privatesite');
                })
                ->select(
                    'ttu.id',
                    DB::raw("'TTU' as scope"),
                    DB::raw("'' as name"),
                    DB::raw("CASE 
                        WHEN ttu.location_type = 'hotel' THEN hotel.address
                        WHEN ttu.location_type = 'statepark' THEN statepark.address
                        WHEN ttu.location_type = 'privatesite' THEN privatesite.address
                        ELSE '' END as address"),
                    DB::raw("CASE 
                        WHEN ttu.location_type = 'hotel' THEN hotel.phone
                        WHEN ttu.location_type = 'statepark' THEN statepark.phone
                        WHEN ttu.location_type = 'privatesite' THEN privatesite.phone
                        ELSE '' END as phone"),
                    'ttu.author'
                );
            if ($keyword) {
                $query->where('author', 'like', "%$keyword%");
            }
            $results = $query->get();
        } elseif ($scope === 'locations') {
            $locations = collect()
                ->merge(DB::table('hotel')->select(
                    'hotel.id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'hotel' as location_type"), // Add location_type
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('statepark')->select(
                    'statepark.id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'statepark' as location_type"), // Add location_type
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('privatesite')->select(
                    'privatesite.id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'privatesite' as location_type"), // Add location_type
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
                    'id',
                    DB::raw("'Survivor' as scope"),
                    DB::raw("CONCAT(fname, ' ', lname) as name"),
                    'address',
                    DB::raw("CONCAT_WS('\n', primary_phone, secondary_phone) as phone"),
                    DB::raw("'' as author")
                );
            $ttus = DB::table('ttu')
                ->leftJoin('hotel', function($join) {
                    $join->on('ttu.location', '=', 'hotel.name')
                         ->where('ttu.location_type', '=', 'hotel');
                })
                ->leftJoin('statepark', function($join) {
                    $join->on('ttu.location', '=', 'statepark.name')
                         ->where('ttu.location_type', '=', 'statepark');
                })
                ->leftJoin('privatesite', function($join) {
                    $join->on('ttu.location', '=', 'privatesite.name')
                         ->where('ttu.location_type', '=', 'privatesite');
                })
                ->select(
                    'ttu.id',
                    DB::raw("'TTU' as scope"),
                    DB::raw("'' as name"),
                    DB::raw("CASE 
                        WHEN ttu.location_type = 'hotel' THEN hotel.address
                        WHEN ttu.location_type = 'statepark' THEN statepark.address
                        WHEN ttu.location_type = 'privatesite' THEN privatesite.address
                        ELSE '' END as address"),
                    DB::raw("CASE 
                        WHEN ttu.location_type = 'hotel' THEN hotel.phone
                        WHEN ttu.location_type = 'statepark' THEN statepark.phone
                        WHEN ttu.location_type = 'privatesite' THEN privatesite.phone
                        ELSE '' END as phone"),
                    'ttu.author'
                );
            $locations = collect()
                ->merge(DB::table('hotel')->select(
                    'id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'hotel' as location_type"), // Add location_type
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('statepark')->select(
                    'id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'statepark' as location_type"), // Add location_type
                    'name', 'address', 'phone', 'author'
                )->get())
                ->merge(DB::table('privatesite')->select(
                    'id',
                    DB::raw("'Location' as scope"),
                    DB::raw("'privatesite' as location_type"), // Add location_type
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

        // Apply additional filters
        $filterFields = $request->input('filter_field', []);
        $filterValues = $request->input('filter_value', []);

        $hasActiveFilter = false;
        foreach ($filterFields as $i => $field) {
            $value = $filterValues[$i] ?? null;
            if ($field && $value !== null && $value !== '') {
                $hasActiveFilter = true;
                $results = $results->filter(function($row) use ($field, $value) {
                    return stripos($row->$field ?? '', $value) !== false;
                });
            }
        }

        // GROUPING LOGIC
        if ($countBy && in_array($countBy, ['scope', 'name', 'address', 'phone', 'author'])) {
            // Group and count
            $grouped = $results->groupBy($countBy)->map(function($group, $key) use ($countBy, $scope) {
                $first = $group->first();
                $row = [];
                // Always include the countBy column
                $row[$countBy] = $key;
                // If scope is not 'all', include the scope value from the first row
                if ($scope !== 'all') {
                    $row['scope'] = $first->scope ?? '';
                }
                $row['count'] = $group->count();
                return (object)$row;
            })->values();

            // Build columns array
            $detailedSearchColumns = [
                ['key' => 'index', 'label' => 'No.'],
            ];
            if ($scope !== 'all') {
                $detailedSearchColumns[] = ['key' => 'scope', 'label' => 'Scope'];
            }
            // Only add $countBy column if not already present
            if (!collect($detailedSearchColumns)->pluck('key')->contains($countBy)) {
                $detailedSearchColumns[] = ['key' => $countBy, 'label' => ucfirst($countBy)];
            }
            $detailedSearchColumns[] = ['key' => 'count', 'label' => 'Count'];

            $results = $grouped;
        }

        return view('admin.detailedSearch', [
            'results' => $results instanceof \Illuminate\Support\Collection ? $results->values()->all() : $results,
            'detailedSearchColumns' => $detailedSearchColumns,
            'columns' => $detailedSearchColumns, // for your JS
            'scope' => $scope,
            'keyword' => $keyword,
            'count_by' => $countBy,
        ]);
    }
}
