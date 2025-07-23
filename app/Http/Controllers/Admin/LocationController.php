<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function locations()
    {
        // Fetch hotels with survivor hh_size sum
        $hotels = \DB::table('hotel')
            ->select(
                'hotel.id',
                \DB::raw("'hotel' as type"),
                'hotel.name as location_name',
                'hotel.address',
                'hotel.phone',
                // Sum hh_size from survivors assigned to rooms in this hotel
                \DB::raw('COALESCE((
                    SELECT SUM(s.hh_size)
                    FROM room r
                    JOIN survivor s ON r.survivor_id = s.id
                    WHERE r.hotel_id = hotel.id
                ), 0) as survivor_count'),
                'hotel.created_at'
            )
            ->get();

        // Fetch state parks with survivor hh_size sum
        $stateparks = \DB::table('statepark')
            ->select(
                'statepark.id', // <-- Add this line!
                \DB::raw("'statepark' as type"),
                'statepark.name as location_name',
                'statepark.address',
                'statepark.phone',
                // Sum hh_size from survivors assigned to lodge_units in this state park
                \DB::raw('COALESCE((
                    SELECT SUM(s.hh_size)
                    FROM lodge_unit lu
                    JOIN survivor s ON lu.survivor_id = s.id
                    WHERE lu.statepark_id = statepark.id
                ), 0) as survivor_count'),
                'statepark.created_at'
            )
            ->get();

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
                \DB::raw('COALESCE(survivor.hh_size, 0) as survivor_count'),
                'privatesite.created_at'
            )
            ->get();

        // Merge all collections and sort by created_at descending
        $locations = $hotels->merge($stateparks)->merge($privatesites)->sortByDesc('created_at')->values();

        // Define the fields you want to show/filter
        $fields = [
            'type',
            'location_name',
            'address',
            'phone',
            'survivor_count',
            // add more fields if needed
        ];

        return view('admin.locations', compact('locations', 'fields'));
    }

    public function locationEdit(Request $request)
    {
        $id = $request->query('id');
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

        return view('admin.locationsEdit', compact('location', 'type', 'rooms', 'lodge_units', 'privatesite', 'ttu'));
    }

    public function locationUpdate(Request $request, $id)
    {
        $type = $request->input('type');
        $data = $request->only(['name', 'address', 'phone']);
        $data['updated_at'] = now();
        $data['author'] = auth()->user()->name ?? 'Unknown';

        if ($type === 'hotel') {
            \DB::table('hotel')->where('id', $id)->update($data);
        } elseif ($type === 'statepark') {
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
        $data = $request->only(['name', 'address', 'phone']);
        $data['author'] = auth()->user()->name ?? 'Unknown'; 
        $data['created_at'] = now(); // Optional: ensure created_at is set
        $data['updated_at'] = now(); // Optional: ensure updated_at is set

        if ($type === 'hotel') {
            \DB::table('hotel')->insert($data);
        } elseif ($type === 'statepark') {
            \DB::table('statepark')->insert($data);
        }
        return redirect()->route('admin.locations')->with('success', 'Location added!');
    }

    public function deleteLocation($id, Request $request)
    {
        $type = $request->query('type');
        \Log::info("DeleteLocation called for id=$id, type=$type");

        if ($type === 'hotel') {
            \DB::table('room')->where('hotel_id', $id)->delete();
            \DB::table('hotel')->where('id', $id)->delete();
        } elseif ($type === 'statepark') {
            \DB::table('lodge_unit')->where('statepark_id', $id)->delete();
            \DB::table('statepark')->where('id', $id)->delete();
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

    public function locationView(Request $request)
    {
        $id = $request->query('id');
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

        return view('admin.locationsEdit', compact(
            'location', 'type', 'rooms', 'lodge_units', 'privatesite', 'ttu', 'readonly'
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
}
