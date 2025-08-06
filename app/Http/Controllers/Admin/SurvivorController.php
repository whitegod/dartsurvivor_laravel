<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Survivor;

class SurvivorController extends Controller
{
    public function survivors(Request $request)
    {
        $query = \DB::table('survivor'); // changed from 'survivors'

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('fema_id', 'LIKE', "%$search%")
                  ->orWhere('fname', 'LIKE', "%$search%")
                  ->orWhere('lname', 'LIKE', "%$search%")
                  ->orWhere('address', 'LIKE', "%$search%")
                  ->orWhere('primary_phone', 'LIKE', "%$search%")
                  ->orWhere('secondary_phone', 'LIKE', "%$search%")
                  ->orWhere('hh_size', 'LIKE', "%$search%")
                  ->orWhere('li_date', 'LIKE', "%$search%");
        }

        $survivors = $query->get();
        $fields = \Schema::getColumnListing('survivor'); // changed from 'survivors'
        return view('admin.survivors', compact('survivors', 'fields'));
    }

    public function viewSurvivor($id)
    {
        $survivor = \App\Survivor::findOrFail($id);
        $ttus = [];
        $hotelName = '';
        $hotelRoom = '';
        $hotelLiDate = '';
        $hotelLoDate = '';
        $stateparkName = '';
        $unitName = '';
        $stateparkLiDate = '';
        $stateparkLoDate = '';

        $locationType = [];
        if (!empty($survivor->location_type)) {
            $locationType = json_decode($survivor->location_type, true) ?? [];
        }

        // TTU: get all assigned TTUs
        if ($survivor && in_array('TTU', $locationType)) {
            $ttus = \App\TTU::where('survivor_id', $survivor->id)->get();
        }

        // Hotel
        $hotelRooms = [];
        if ($survivor && in_array('Hotel', $locationType)) {
            $hotelRooms = \App\Room::where('survivor_id', $survivor->id)->with('hotel')->get();
        }
        if (empty($hotelRooms) || (is_object($hotelRooms) && $hotelRooms->count() === 0) || (is_array($hotelRooms) && count($hotelRooms) === 0)) {
            $hotelRooms = [null];
        }

        // State Park
        $stateparkUnits = [];
        if ($survivor && in_array('State Park', $locationType)) {
            $stateparkUnits = \DB::table('lodge_unit')
                ->join('statepark', 'lodge_unit.statepark_id', '=', 'statepark.id')
                ->where('lodge_unit.survivor_id', $survivor->id)
                ->select('lodge_unit.*', 'statepark.name as statepark_name')
                ->get();
        }
        if (empty($stateparkUnits) || (is_object($stateparkUnits) && $stateparkUnits->count() === 0) || (is_array($stateparkUnits) && count($stateparkUnits) === 0)) {
            $stateparkUnits = [null];
        }

        $readonly = true;

        if (empty($ttus) || (is_object($ttus) && $ttus->count() === 0) || (is_array($ttus) && count($ttus) === 0)) {
            $ttus = [null];
        }
        return view('admin.survivorsEdit', compact(
            'survivor', 'ttus', 'hotelRooms', 'stateparkUnits',
            'hotelName', 'hotelRoom', 'hotelLiDate', 'hotelLoDate',
            'stateparkName', 'unitName', 'stateparkLiDate', 'stateparkLoDate',
            'locationType', 'readonly'
        ));
    }

    public function editSurvivor($id = null)
    {
        $survivor = $id === 'new' ? null : \App\Survivor::find($id);
        $ttus = [];
        $hotelName = '';
        $hotelRoom = '';
        $hotelLiDate = '';
        $hotelLoDate = '';
        $stateparkName = '';
        $unitName = '';
        $stateparkLiDate = '';
        $stateparkLoDate = '';
        
        $locationType = [];
        if (!empty($survivor->location_type)) {
            $locationType = json_decode($survivor->location_type, true) ?? [];
        }

        // TTU: get all assigned TTUs
        if ($survivor && in_array('TTU', $locationType)) {
            $ttus = \App\TTU::where('survivor_id', $survivor->id)->get();
        }

        // Hotel
        $hotelRooms = [];
        if ($survivor && in_array('Hotel', $locationType)) {
            $hotelRooms = \App\Room::where('survivor_id', $survivor->id)->with('hotel')->get();
        }
        if (empty($hotelRooms) || (is_object($hotelRooms) && $hotelRooms->count() === 0) || (is_array($hotelRooms) && count($hotelRooms) === 0)) {
            $hotelRooms = [null];
        }

        // State Park
        $stateparkUnits = [];
        if ($survivor && in_array('State Park', $locationType)) {
            $stateparkUnits = \DB::table('lodge_unit')
                ->join('statepark', 'lodge_unit.statepark_id', '=', 'statepark.id')
                ->where('lodge_unit.survivor_id', $survivor->id)
                ->select('lodge_unit.*', 'statepark.name as statepark_name')
                ->get();
        }
        if (empty($stateparkUnits) || (is_object($stateparkUnits) && $stateparkUnits->count() === 0) || (is_array($stateparkUnits) && count($stateparkUnits) === 0)) {
            $stateparkUnits = [null];
        }

        if (empty($ttus) || (is_object($ttus) && $ttus->count() === 0) || (is_array($ttus) && count($ttus) === 0)) {
            $ttus = [null];
        }
        return view('admin.survivorsEdit', compact(
            'survivor', 'ttus', 'hotelRooms', 'stateparkUnits',
            'hotelName', 'hotelRoom', 'hotelLiDate', 'hotelLoDate',
            'stateparkName', 'unitName', 'stateparkLiDate', 'stateparkLoDate',
            'locationType'
        ));
    }

    public function storeSurvivor(Request $request)
    {
        // Validate pets field
        $request->validate([
            'pets' => ['nullable', 'integer', 'max:2'],
        ], [
            'pets.max' => 'FEMA limits pets as 2 at max.',
        ]);

        $data = $request->except([
            'vin', 'lo', 'lo_date', 'est_lo_date',
            'hotel_name', 'hotel_room', 'hotel_li_date', 'hotel_lo_date',
            'statepark_name', 'unit_name', 'statepark_li_date', 'statepark_lo_date'
        ]);

        // Ensure group fields are included for saving
        $groupFields = [
            'group0_2', 'group3_6', 'group7_12', 'group13_17',
            'group18_21', 'group22_65', 'group65plus'
        ];
        foreach ($groupFields as $field) {
            $data[$field] = $request->input($field, 0);
        }

        // Set authored to current user name
        $data['author'] = auth()->user()->name ?? 'Unknown';

        // Save location_type as JSON
        $locationType = $request->input('location_type', []);
        $data['location_type'] = json_encode($locationType);

        $survivor = Survivor::create($data);

        if (is_array($locationType) && in_array('TTU', $locationType) && $request->vin) {
            $vins = $request->input('vin', []);
            $li_dates = $request->input('li_date', []);
            $los = $request->input('lo', []);
            $lo_dates = $request->input('lo_date', []);
            $est_lo_dates = $request->input('est_lo_date', []);

            foreach ($vins as $i => $vin) {
                if (empty($vin)) continue;
                $ttu = \App\TTU::where('vin', $vin)->first();
                if ($ttu) {
                    $ttu->li_date = !empty($li_dates[$i]) ? $li_dates[$i] : null;
                    $ttu->lo = $los[$i] ?? 0;
                    $ttu->lo_date = !empty($lo_dates[$i]) ? $lo_dates[$i] : null;
                    $ttu->est_lo_date = !empty($est_lo_dates[$i]) ? $est_lo_dates[$i] : null;
                    $ttu->survivor_id = $survivor->id;
                    $ttu->save();
                }
            }
        } else {
            // Unassign all TTUs from this survivor
            \App\TTU::where('survivor_id', $survivor->id)->update([
                'li_date' => null,
                'lo' => null,
                'lo_date' => null,
                'est_lo_date' => null,
                'survivor_id' => null,
            ]);
        }

        // Hotel
        if (is_array($locationType) && in_array('Hotel', $locationType)) {
            $hotel_names = $request->input('hotel_name', []);
            $hotel_rooms = $request->input('hotel_room', []);
            $hotel_li_dates = $request->input('hotel_li_date', []);
            $hotel_lo_dates = $request->input('hotel_lo_date', []);

            // (Optional) Unassign all previous hotel rooms from this survivor first
            \App\Room::where('survivor_id', $survivor->id)->update([
                'li_date' => null,
                'lo_date' => null,
                'survivor_id' => null,
            ]);

            foreach ($hotel_names as $i => $hotel_name) {
                if (empty($hotel_name) || empty($hotel_rooms[$i])) continue;

                $hotel = \App\Hotel::firstOrCreate(['name' => $hotel_name], ['name' => $hotel_name]);
                \App\Room::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'room_num' => $hotel_rooms[$i],
                    ],
                    [
                        'hotel_id' => $hotel->id,
                        'room_num' => $hotel_rooms[$i],
                        'li_date' => $hotel_li_dates[$i] ?? null,
                        'lo_date' => $hotel_lo_dates[$i] ?? null,
                        'survivor_id' => $survivor->id,
                    ]
                );
            }
        } else {
            // If Hotel is unchecked, clear Room fields for this survivor
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $room->li_date = null;
                $room->lo_date = null;
                $room->survivor_id = null;
                $room->save();
            }
        }

        // State Park
        if (is_array($locationType) && in_array('State Park', $locationType)) {
            $statepark_names = $request->input('statepark_name', []);
            $unit_names = $request->input('unit_name', []);
            $li_dates = $request->input('statepark_li_date', []);
            $lo_dates = $request->input('statepark_lo_date', []);

            // Unassign all previous units from this survivor first
            \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->update([
                'survivor_id' => null,
                'li_date' => null,
                'lo_date' => null,
            ]);

            foreach ($statepark_names as $i => $statepark_name) {
                if (empty($statepark_name) || empty($unit_names[$i])) continue;
                $parkRow = \DB::table('statepark')->where('name', $statepark_name)->first();
                if ($parkRow) {
                    $unit = \DB::table('lodge_unit')
                        ->where('statepark_id', $parkRow->id)
                        ->where('unit_name', $unit_names[$i])
                        ->first();
                    if ($unit) {
                        \DB::table('lodge_unit')
                            ->where('id', $unit->id)
                            ->update([
                                'survivor_id' => $survivor->id,
                                'li_date' => $li_dates[$i] ?? null,
                                'lo_date' => $lo_dates[$i] ?? null,
                            ]);
                    }
                }
            }
        } else {
            // Unassign all units if State Park is unchecked
            \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->update([
                'survivor_id' => null,
                'li_date' => null,
                'lo_date' => null,
            ]);
        }

        return redirect()->route('admin.survivors')->with('success', 'Survivor added successfully!');
    }

    public function updateSurvivor(Request $request, $id)
    {
        // Validate pets field
        $request->validate([
            'pets' => ['nullable', 'integer', 'max:2'],
        ], [
            'pets.max' => 'FEMA limits pets as 2 at max.',
        ]);

        $data = $request->except([
            'vin', 'lo', 'lo_date', 'est_lo_date',
            'hotel_name', 'hotel_room', 'hotel_li_date', 'hotel_lo_date',
            'statepark_name', 'unit_name', 'statepark_li_date', 'statepark_lo_date'
        ]);

        // Ensure group fields are included for updating
        $groupFields = [
            'group0_2', 'group3_6', 'group7_12', 'group13_17',
            'group18_21', 'group22_65', 'group65plus'
        ];
        foreach ($groupFields as $field) {
            $data[$field] = $request->input($field, 0);
        }

        // Set author to current user name
        $data['author'] = auth()->user()->name ?? 'Unknown';

        // Save location_type as JSON
        $locationType = $request->input('location_type', []);
        $data['location_type'] = json_encode($locationType);

        $survivor = Survivor::findOrFail($id);
        $survivor->update($data);

        // TTU
        if (is_array($locationType) && in_array('TTU', $locationType) && $request->vin) {
            $vins = $request->input('vin', []);
            $li_dates = $request->input('li_date', []);
            $los = $request->input('lo', []);
            $lo_dates = $request->input('lo_date', []);
            $est_lo_dates = $request->input('est_lo_date', []);

            // (For updateSurvivor only) Unassign all previous TTUs from this survivor first
            \App\TTU::where('survivor_id', $survivor->id)->update([
                'li_date' => null,
                'lo' => null,
                'lo_date' => null,
                'est_lo_date' => null,
                'survivor_id' => null,
            ]);

            foreach ($vins as $i => $vin) {
                if (empty($vin)) continue;
                $ttu = \App\TTU::where('vin', $vin)->first();
                if ($ttu) {
                    $ttu->li_date = !empty($li_dates[$i]) ? $li_dates[$i] : null;
                    $ttu->lo = $los[$i] ?? 0;
                    $ttu->lo_date = !empty($lo_dates[$i]) ? $lo_dates[$i] : null;
                    $ttu->est_lo_date = !empty($est_lo_dates[$i]) ? $est_lo_dates[$i] : null;
                    $ttu->survivor_id = $survivor->id;
                    $ttu->save();
                }
            }
        } else {
            // Unassign all TTUs from this survivor
            \App\TTU::where('survivor_id', $survivor->id)->update([
                'li_date' => null,
                'lo' => null,
                'lo_date' => null,
                'est_lo_date' => null,
                'survivor_id' => null,
            ]);
        }

        // Hotel
        if (is_array($locationType) && in_array('Hotel', $locationType)) {
            $hotel_names = $request->input('hotel_name', []);
            $hotel_rooms = $request->input('hotel_room', []);
            $hotel_li_dates = $request->input('hotel_li_date', []);
            $hotel_lo_dates = $request->input('hotel_lo_date', []);

            // (Optional) Unassign all previous hotel rooms from this survivor first
            \App\Room::where('survivor_id', $survivor->id)->update([
                'li_date' => null,
                'lo_date' => null,
                'survivor_id' => null,
            ]);

            foreach ($hotel_names as $i => $hotel_name) {
                if (empty($hotel_name) || empty($hotel_rooms[$i])) continue;

                $hotel = \App\Hotel::firstOrCreate(['name' => $hotel_name], ['name' => $hotel_name]);
                \App\Room::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'room_num' => $hotel_rooms[$i],
                    ],
                    [
                        'hotel_id' => $hotel->id,
                        'room_num' => $hotel_rooms[$i],
                        'li_date' => $hotel_li_dates[$i] ?? null,
                        'lo_date' => $hotel_lo_dates[$i] ?? null,
                        'survivor_id' => $survivor->id,
                    ]
                );
            }
        } else {
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $room->li_date = null;
                $room->lo_date = null;
                $room->survivor_id = null;
                $room->save();
            }
        }

        // State Park
        if (is_array($locationType) && in_array('State Park', $locationType)) {
            $statepark_names = $request->input('statepark_name', []);
            $unit_names = $request->input('unit_name', []);
            $li_dates = $request->input('statepark_li_date', []);
            $lo_dates = $request->input('statepark_lo_date', []);

            // Unassign all previous units from this survivor first
            \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->update([
                'survivor_id' => null,
                'li_date' => null,
                'lo_date' => null,
            ]);

            foreach ($statepark_names as $i => $statepark_name) {
                if (empty($statepark_name) || empty($unit_names[$i])) continue;
                $parkRow = \DB::table('statepark')->where('name', $statepark_name)->first();
                if ($parkRow) {
                    $unit = \DB::table('lodge_unit')
                        ->where('statepark_id', $parkRow->id)
                        ->where('unit_name', $unit_names[$i])
                        ->first();
                    if ($unit) {
                        \DB::table('lodge_unit')
                            ->where('id', $unit->id)
                            ->update([
                                'survivor_id' => $survivor->id,
                                'li_date' => $li_dates[$i] ?? null,
                                'lo_date' => $lo_dates[$i] ?? null,
                            ]);
                    }
                }
            }
        } else {
            // Unassign all units if State Park is unchecked
            \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->update([
                'survivor_id' => null,
                'li_date' => null,
                'lo_date' => null,
            ]);
        }

        return redirect()->route('admin.survivors')->with('success', 'Survivor updated successfully.');
    }

    public function deleteSurvivor($id)
    {
        \DB::table('survivor')->where('id', $id)->delete(); // changed from 'survivors'

        return redirect()->route('admin.survivors')->with('success', 'Survivor deleted successfully!');
    }
}
