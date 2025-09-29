<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LocationController extends Controller
{
    public function locations()
    {
        // apply FDEC filter (from URL ?fdec_id or ?fdec-filter, or cookie set by client-side script)
        $fdecFilter = request()->query('fdec_id')
            ?? request()->query('fdec-filter')
            ?? request()->cookie('fdecFilter')
            ?? null;

        $fdecIds = [];
        if (!empty($fdecFilter)) {
            $raw = is_array($fdecFilter) ? $fdecFilter : preg_split('/\s*,\s*/', trim((string)$fdecFilter));
            $fdecIds = array_values(array_filter(array_map(function($v){ return trim((string)$v); }, (array)$raw)));
        }

        // Fetch hotels with survivor hh_size sum
        $hotels = \DB::table('hotel')
            ->select(
                'hotel.id',
                \DB::raw("'hotel' as type"),
                'hotel.name as location_name',
                'hotel.address',
                'hotel.phone',
                'hotel.contact_name',
                'hotel.fdec_id',
                // Sum hh_size from survivors assigned to rooms in this hotel
                \DB::raw('COALESCE((
                    SELECT SUM(s.hh_size)
                    FROM room r
                    JOIN survivor s ON r.survivor_id = s.id
                    WHERE r.hotel_id = hotel.id
                ), 0) as survivor_count'),
                'hotel.created_at'
            );

        // apply FDEC filter to hotels if requested
        if (!empty($fdecIds)) {
            try {
                $colType = \Schema::getColumnType('hotel', 'fdec_id');
            } catch (\Exception $e) {
                $colType = null;
            }

            if ($colType === 'json') {
                $hotels->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhereJsonContains('hotel.fdec_id', (string)$id);
                    }
                });
            } else {
                $hotels->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhere('hotel.fdec_id', 'like', '%"' . (string)$id . '"%');
                    }
                });
            }
        }

        $hotels = $hotels->get();

        // Fetch state parks with survivor hh_size sum
        $stateparks = \DB::table('statepark')
            ->select(
                'statepark.id', // <-- Add this line!
                \DB::raw("'statepark' as type"),
                'statepark.name as location_name',
                'statepark.address',
                'statepark.phone',
                'statepark.contact_name',
                'statepark.fdec_id',
                // Sum hh_size from survivors assigned to lodge_units in this state park
                \DB::raw('COALESCE((
                    SELECT SUM(s.hh_size)
                    FROM lodge_unit lu
                    JOIN survivor s ON lu.survivor_id = s.id
                    WHERE lu.statepark_id = statepark.id
                ), 0) as survivor_count'),
                'statepark.created_at'
            );

        // apply FDEC filter to stateparks if requested
        if (!empty($fdecIds)) {
            try {
                $colType = \Schema::getColumnType('statepark', 'fdec_id');
            } catch (\Exception $e) {
                $colType = null;
            }

            if ($colType === 'json') {
                $stateparks->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhereJsonContains('statepark.fdec_id', (string)$id);
                    }
                });
            } else {
                $stateparks->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhere('statepark.fdec_id', 'like', '%"' . (string)$id . '"%');
                    }
                });
            }
        }

        $stateparks = $stateparks->get();

        // Fetch privatesite locations with survivor_count (hh_size from survivor via ttu)
        $privatesites = \DB::table('privatesite')
            ->leftJoin('ttu', 'privatesite.ttu_id', '=', 'ttu.id')
            ->leftJoin('survivor', 'ttu.survivor_id', '=', 'survivor.id')
            ->select(
                'privatesite.id',
                \DB::raw("'privatesite' as type"),
                'privatesite.name as location_name',
                'privatesite.address',
                'privatesite.phone',
                'privatesite.contact_name',
                'privatesite.fdec_id',
                \DB::raw('COALESCE(survivor.hh_size, 0) as survivor_count'),
                'privatesite.created_at'
            );

        // apply FDEC filter to privatesites if requested
        if (!empty($fdecIds)) {
            try {
                $colType = \Schema::getColumnType('privatesite', 'fdec_id');
            } catch (\Exception $e) {
                $colType = null;
            }

            if ($colType === 'json') {
                $privatesites->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhereJsonContains('privatesite.fdec_id', (string)$id);
                    }
                });
            } else {
                $privatesites->where(function($q) use ($fdecIds) {
                    foreach ($fdecIds as $id) {
                        if ($id === '') continue;
                        $q->orWhere('privatesite.fdec_id', 'like', '%"' . (string)$id . '"%');
                    }
                });
            }
        }

        $privatesites = $privatesites->get();

        // Merge all collections and sort by created_at descending
        $locations = $hotels->merge($stateparks)->merge($privatesites)->sortByDesc('created_at')->values();

        // attach human-readable FDEC labels to each location (like TTUController)
        $fdecMap = \DB::table('fdec')->pluck('fdec_no', 'id')->all();
        foreach ($locations as $loc) {
            $labels = [];
            $ids = $loc->fdec_id ?? [];
            if (is_string($ids) && $ids !== '') {
                $decoded = json_decode($ids, true);
                $ids = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($ids)) {
                $ids = [];
            }
            foreach ($ids as $id) {
                if ($id === null || $id === '') continue;
                $labels[] = $fdecMap[$id] ?? $fdecMap[(int)$id] ?? (string)$id;
            }
            $loc->fdec = $labels ? implode(', ', $labels) : '';
        }

        // Define the fields you want to show/filter
        $fields = [
            'type',
            'location_name',
            'address',
            'phone',
            'survivor_count',
            // add more fields if needed
        ];

    // provide FDEC list for the header filter and table label mapping
    $fdecList = \DB::table('fdec')->orderBy('fdec_no')->get();

    return view('admin.locations', compact('locations', 'fields', 'fdecList', 'fdecFilter'));
    }

    public function locationEdit(Request $request, $id)
    {
        $type = $request->query('type');
        $location = null;
        $rooms = null;
        $lodge_units = null;
        $privatesite = null;

        if ($id && $type === 'hotel') {
            $location = \DB::table('hotel')->where('id', $id)->first();
            $rooms = \DB::table('room')
                ->leftJoin('survivor', 'room.survivor_id', '=', 'survivor.id')
                ->select('room.id as room_id', 'room.room_num', 'survivor.id', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('room.hotel_id', $id)
                ->get()
                ->map(function($r) {
                    $r->survivor_name = $r->fname ? $r->fname . ' ' . $r->lname : null;
                    return $r;
                });
        } elseif ($id && $type === 'statepark') {
            $location = \DB::table('statepark')->where('id', $id)->first();
            $lodge_units = \DB::table('lodge_unit')
                ->leftJoin('survivor', 'lodge_unit.survivor_id', '=', 'survivor.id')
                ->select('lodge_unit.id as lodge_unit_id', 'lodge_unit.unit_type', 'lodge_unit.unit_name', 'survivor.id', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('lodge_unit.statepark_id', $id)
                ->get()
                ->map(function($u) {
                    $u->survivor_name = $u->fname ? $u->fname . ' ' . $u->lname : null;
                    return $u;
                });
        } elseif ($id && $type === 'privatesite') {
            $privatesite = \DB::table('privatesite')->where('id', $id)->first();
            $ttu = null;
            if ($privatesite && $privatesite->ttu_id) {
                $ttu = \DB::table('ttu')->where('id', $privatesite->ttu_id)->first();
            }
        }

        if (!isset($ttu)) {
            $ttu = null;
        }

        // provide FDEC list for the select control
        $fdecList = \DB::table('fdec')->get();

        return view('admin.locationsEdit', compact('location', 'type', 'rooms', 'lodge_units', 'privatesite', 'ttu', 'fdecList'));
    }

    public function locationUpdate(Request $request, $id)
    {
        $type = $request->input('type');
        $data = $request->only(['name', 'address', 'phone', 'contact_name']); // <-- add contact_name
        $data['updated_at'] = now();
        $data['author'] = auth()->user()->name ?? 'Unknown';

        // Handle optional FDEC input (store as JSON array) if the table has the column
        $fdecIds = $request->input('fdec_id', []);
        if (!is_array($fdecIds)) {
            // attempt to parse comma-separated string
            $fdecIds = $fdecIds === null || $fdecIds === '' ? [] : preg_split('/\s*,\s*/', (string)$fdecIds);
        }

        if ($type === 'hotel') {
            if (Schema::hasColumn('hotel', 'fdec_id')) {
                $data['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            \DB::table('hotel')->where('id', $id)->update($data);
        } elseif ($type === 'statepark') {
            if (Schema::hasColumn('statepark', 'fdec_id')) {
                $data['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            \DB::table('statepark')->where('id', $id)->update($data);
        } elseif ($type === 'privatesite') {
            $privatesiteData = [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'pow' => $request->has('pow') ? 1 : 0,
                'h2o' => $request->has('h2o') ? 1 : 0,
                'sew' => $request->has('sew') ? 1 : 0,
                'own' => $request->has('own') ? 1 : 0,
                'res' => $request->has('res') ? 1 : 0,
                'damage_assessment' => $request->input('damage_assessment'),
                'ehp' => $request->input('ehp'),
                'ehp_notes' => $request->input('ehp_notes'),
                'dow_long' => $request->input('dow_long'),
                'dow_lat' => $request->input('dow_lat'),
                'zon' => $request->input('zon'),
                'dow_response' => $request->input('dow_response'),
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('privatesite', 'fdec_id')) {
                $privatesiteData['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            $updated = \DB::table('privatesite')->where('id', $id)->update($privatesiteData);
            if ($updated === 0) {
                \Log::warning("No privatesite row updated for id=$id");
            }
        }

        return redirect()->route('admin.locations')->with('success', 'Location updated!');
    }

    public function locationStore(Request $request)
    {
        $type = $request->input('type');
        $data = $request->only(['name', 'address', 'phone', 'contact_name']); // <-- add contact_name
        $data['author'] = auth()->user()->name ?? 'Unknown'; 
        $data['created_at'] = now(); // Optional: ensure created_at is set
        $data['updated_at'] = now(); // Optional: ensure updated_at is set

        // handle optional fdec ids
        $fdecIds = $request->input('fdec_id', []);
        if (!is_array($fdecIds)) {
            $fdecIds = $fdecIds === null || $fdecIds === '' ? [] : preg_split('/\s*,\s*/', (string)$fdecIds);
        }
        if ($type === 'hotel') {
            if (Schema::hasColumn('hotel', 'fdec_id')) {
                $data['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            \DB::table('hotel')->insert($data);
        } elseif ($type === 'statepark') {
            if (Schema::hasColumn('statepark', 'fdec_id')) {
                $data['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            \DB::table('statepark')->insert($data);
        } elseif ($type === 'privatesite') {
            $privatesiteData = [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'pow' => $request->has('pow') ? 1 : 0,
                'h2o' => $request->has('h2o') ? 1 : 0,
                'sew' => $request->has('sew') ? 1 : 0,
                'own' => $request->has('own') ? 1 : 0,
                'res' => $request->has('res') ? 1 : 0,
                'damage_assessment' => $request->input('damage_assessment'),
                'ehp' => $request->input('ehp'),
                'ehp_notes' => $request->input('ehp_notes'),
                'dow_long' => $request->input('dow_long'),
                'dow_lat' => $request->input('dow_lat'),
                'zon' => $request->input('zon'),
                'dow_response' => $request->input('dow_response'),
            ];
            if (Schema::hasColumn('privatesite', 'fdec_id')) {
                $privatesiteData['fdec_id'] = json_encode(array_values(array_filter($fdecIds)));
            }
            \DB::table('privatesite')->insert($privatesiteData);
        }
        return redirect()->route('admin.locations')->with('success', 'Location added!');
    }

    public function deleteLocation($id, Request $request)
    {
        $type = $request->query('type');

        if ($type === 'hotel') {
            \DB::table('room')->where('hotel_id', $id)->delete();
            \DB::table('hotel')->where('id', $id)->delete();
        } elseif ($type === 'statepark') {
            \DB::table('lodge_unit')->where('statepark_id', $id)->delete();
            \DB::table('statepark')->where('id', $id)->delete();
        } elseif ($type === 'privatesite') {
            $privatesite = \DB::table('privatesite')->where('id', $id)->first();
            if ($privatesite && $privatesite->ttu_id) {
                // Remove privatesite reference from TTU
                \DB::table('ttu')->where('id', $privatesite->ttu_id)->update([
                    'location_type' => null,
                    'location' => null,
                ]);
            }
            \DB::table('privatesite')->where('id', $id)->delete();
        }
        return redirect()->route('admin.locations')->with('success', 'Location and related units deleted!');
    }

    public function roomStore(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:hotel,id',
            'number' => 'required|string|max:255',
        ]);

        \DB::table('room')->insert([
            'hotel_id' => $validated['location_id'],
            'room_num' => $validated['number'],
        ]);

        return redirect()->back()->with('success', 'Room added successfully!');
    }

    public function lodgeUnitStore(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:statepark,id',
            'number' => 'required|string|max:255',
            'unit_type' => 'required|string', // <-- validate unit_type
        ]);

        \DB::table('lodge_unit')->insert([
            'statepark_id' => $validated['location_id'],
            'unit_name' => $validated['number'],
            'unit_type' => $validated['unit_type'], // <-- save unit_type
        ]);

        return redirect()->back()->with('success', 'Lodge Unit added successfully!');
    }

    public function locationView(Request $request, $id)
    {
        $type = $request->query('type');
        $location = null;
        $rooms = null;
        $lodge_units = null;
        $privatesite = null;

        if ($id && $type === 'hotel') {
            $location = \DB::table('hotel')->where('id', $id)->first();
            $rooms = \DB::table('room')
                ->leftJoin('survivor', 'room.survivor_id', '=', 'survivor.id')
                ->select('room.id as room_id', 'room.room_num', 'survivor.id', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('room.hotel_id', $id)
                ->get()
                ->map(function($r) {
                    $r->survivor_name = $r->fname ? $r->fname . ' ' . $r->lname : null;
                    return $r;
                });
        } elseif ($id && $type === 'statepark') {
            $location = \DB::table('statepark')->where('id', $id)->first();
            $lodge_units = \DB::table('lodge_unit')
                ->leftJoin('survivor', 'lodge_unit.survivor_id', '=', 'survivor.id')
                ->select('lodge_unit.id as lodge_unit_id', 'lodge_unit.unit_type', 'lodge_unit.unit_name', 'survivor.id', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('lodge_unit.statepark_id', $id)
                ->get()
                ->map(function($u) {
                    $u->survivor_name = $u->fname ? $u->fname . ' ' . $u->lname : null;
                    return $u;
                });
        } elseif ($id && $type === 'privatesite') {
            $privatesite = \DB::table('privatesite')->where('id', $id)->first();
            $ttu = null;
            if ($privatesite && $privatesite->ttu_id) {
                $ttu = \DB::table('ttu')->where('id', $privatesite->ttu_id)->first();
            }
        }

        $readonly = true;
        if (!isset($ttu)) {
            $ttu = null;
        }

        // ensure FDEC list is available in readonly view as well
        $fdecList = \DB::table('fdec')->get();

        return view('admin.locationsEdit', compact(
            'location', 'type', 'rooms', 'lodge_units', 'privatesite', 'ttu', 'readonly', 'fdecList'
        ));
    }

    public function roomUpdate(Request $request, $id) {
        $request->validate([
            'number' => 'required|string|max:255',
        ]);
        \DB::table('room')->where('id', $id)->update([
            'room_num' => $request->number,
        ]);
        return redirect()->back()->with('success', 'Room updated!');
    }

    public function roomDelete($id)
    {
        \Log::info("Trying to delete room: $id");
        $room = \App\Room::find($id);
        if ($room) {
            $room->delete();
        }
        return redirect()->back()->with('success', 'Room deleted successfully!');
    }

    public function lodgeUnitUpdate(Request $request, $id) {
        $request->validate([
            'number' => 'required|string|max:255',
            'unit_type' => 'required|string',
        ]);
        \DB::table('lodge_unit')->where('id', $id)->update([
            'unit_name' => $request->number,
            'unit_type' => $request->unit_type,
        ]);
        return redirect()->back()->with('success', 'Lodge unit updated!');
    }

    public function lodgeUnitDelete($id)
    {
        $unit = \App\LodgeUnit::find($id);
        if ($unit) {
            $unit->delete();
        }
        return redirect()->back()->with('success', 'Lodge unit deleted successfully!');
    }
}
