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
        // Reuse the editSurvivor logic, but set $readonly = true
        $survivor = $id === 'new' ? null : \App\Survivor::find($id);
        $ttu = null;
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

        // TTU
        if ($survivor && in_array('TTU', $locationType)) {
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
        }

        // Hotel
        if ($survivor && in_array('Hotel', $locationType)) {
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $hotel = \App\Hotel::find($room->hotel_id);
                $hotelName = $hotel ? $hotel->name : '';
                $hotelRoom = $room->room_num;
                $hotelLiDate = $room->li_date;
                $hotelLoDate = $room->lo_date;
            }
        }

        // State Park
        if ($survivor && in_array('State Park', $locationType)) {
            $unit = \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->first();
            if ($unit) {
                $park = \DB::table('statepark')->where('id', $unit->statepark_id)->first();
                $stateparkName = $park ? $park->name : '';
                $unitName = $unit->unit_name;
                $stateparkLiDate = $unit->li_date ? date('Y-m-d', strtotime($unit->li_date)) : '';
                $stateparkLoDate = $unit->lo_date ? date('Y-m-d', strtotime($unit->lo_date)) : '';
            }
        }

        $readonly = true;
        return view('admin.survivorsEdit', compact(
            'survivor', 'ttu',
            'hotelName', 'hotelRoom', 'hotelLiDate', 'hotelLoDate',
            'stateparkName', 'unitName', 'stateparkLiDate', 'stateparkLoDate',
            'locationType', 'readonly'
        ));
    }

    public function editSurvivor($id = null)
    {
        $survivor = $id === 'new' ? null : \App\Survivor::find($id);
        $ttu = null;
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

        // TTU
        if ($survivor && in_array('TTU', $locationType)) {
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
        }

        // Hotel
        if ($survivor && in_array('Hotel', $locationType)) {
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $hotel = \App\Hotel::find($room->hotel_id);
                $hotelName = $hotel ? $hotel->name : '';
                $hotelRoom = $room->room_num;
                $hotelLiDate = $room->li_date;
                $hotelLoDate = $room->lo_date;
            }
        }

        // State Park
        if ($survivor && in_array('State Park', $locationType)) {
            $unit = \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->first();
            if ($unit) {
                $park = \DB::table('statepark')->where('id', $unit->statepark_id)->first();
                $stateparkName = $park ? $park->name : '';
                $unitName = $unit->unit_name;
                $stateparkLiDate = $unit->li_date ? date('Y-m-d', strtotime($unit->li_date)) : '';
                $stateparkLoDate = $unit->lo_date ? date('Y-m-d', strtotime($unit->lo_date)) : '';
            }
        }

        return view('admin.survivorsEdit', compact(
            'survivor', 'ttu',
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

        // TTU
        if (is_array($locationType) && in_array('TTU', $locationType) && $request->vin) {
            $ttu = \App\TTU::where('vin', $request->vin)->first();
            if ($ttu) {
                $ttu->li_date = $request->li_date;
                $ttu->lo = $request->lo;
                $ttu->lo_date = $request->lo_date;
                $ttu->est_lo_date = $request->est_lo_date;
                $ttu->survivor_id = $survivor->id;
                $ttu->save();
            }
        } else {
            // If TTU is unchecked, clear TTU fields for this survivor
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
            if ($ttu) {
                $ttu->li_date = null;
                $ttu->lo = null;
                $ttu->lo_date = null;
                $ttu->est_lo_date = null;
                $ttu->survivor_id = null;
                $ttu->save();
            }
        }

        // Hotel
        if (is_array($locationType) && in_array('Hotel', $locationType)) {
            $hotel = \App\Hotel::firstOrCreate(
                ['name' => $request->hotel_name],
                ['name' => $request->hotel_name]
            );

            \App\Room::updateOrCreate(
                [
                    'hotel_id' => $hotel->id,
                    'room_num' => $request->hotel_room,
                ],
                [
                    'hotel_id' => $hotel->id,
                    'room_num' => $request->hotel_room,
                    'li_date' => $request->hotel_li_date,
                    'lo_date' => $request->hotel_lo_date,
                    'survivor_id' => $survivor->id,
                ]
            );
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
            $parkRow = \DB::table('statepark')->where('name', $request->statepark_name)->first();
            if ($parkRow) {
                $unit = \DB::table('lodge_unit')
                    ->where('statepark_id', $parkRow->id)
                    ->where('unit_name', $request->unit_name)
                    ->first();

                if ($unit) {
                    \DB::table('lodge_unit')
                        ->where('id', $unit->id)
                        ->update([
                            'survivor_id' => $survivor->id,
                            'li_date' => $request->statepark_li_date,
                            'lo_date' => $request->statepark_lo_date,
                        ]);
                }
            }
        } else {
            // If State Park is unchecked, clear lodge_unit fields for this survivor
            $unit = \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->first();
            if ($unit) {
                \DB::table('lodge_unit')
                    ->where('id', $unit->id)
                    ->update([
                        'survivor_id' => null,
                        'li_date' => null,
                        'lo_date' => null,
                    ]);
            }
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
            $ttu = \App\TTU::where('vin', $request->vin)->first();
            if ($ttu) {
                $ttu->li_date = $request->li_date;
                $ttu->lo = $request->lo;
                $ttu->lo_date = $request->lo_date;
                $ttu->est_lo_date = $request->est_lo_date;
                $ttu->survivor_id = $survivor->id;
                $ttu->save();
            }
        } else {
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
            if ($ttu) {
                $ttu->li_date = null;
                $ttu->lo = null;
                $ttu->lo_date = null;
                $ttu->est_lo_date = null;
                $ttu->survivor_id = null;
                $ttu->save();
            }
        }

        // Hotel
        if (is_array($locationType) && in_array('Hotel', $locationType)) {
            $hotel = \App\Hotel::firstOrCreate(
                ['name' => $request->hotel_name],
                ['name' => $request->hotel_name]
            );

            $oldRoom = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($oldRoom && $oldRoom->room_num !== $request->hotel_room) {
                $oldRoom->survivor_id = null;
                $oldRoom->li_date = null;
                $oldRoom->lo_date = null;
                $oldRoom->save();
            }

            \App\Room::updateOrCreate(
                [
                    'hotel_id' => $hotel->id,
                    'room_num' => $request->hotel_room,
                ],
                [
                    'hotel_id' => $hotel->id,
                    'room_num' => $request->hotel_room,
                    'li_date' => $request->hotel_li_date,
                    'lo_date' => $request->hotel_lo_date,
                    'survivor_id' => $survivor->id,
                ]
            );
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
            $parkRow = \DB::table('statepark')->where('name', $request->statepark_name)->first();
            if ($parkRow) {
                $unit = \DB::table('lodge_unit')
                    ->where('statepark_id', $parkRow->id)
                    ->where('unit_name', $request->unit_name)
                    ->first();

                if ($unit) {
                    \DB::table('lodge_unit')
                        ->where('id', $unit->id)
                        ->update([
                            'survivor_id' => $survivor->id,
                            'li_date' => $request->statepark_li_date,
                            'lo_date' => $request->statepark_lo_date,
                        ]);
                }
            }
        } else {
            $unit = \DB::table('lodge_unit')->where('survivor_id', $survivor->id)->first();
            if ($unit) {
                \DB::table('lodge_unit')
                    ->where('id', $unit->id)
                    ->update([
                        'survivor_id' => null,
                        'li_date' => null,
                        'lo_date' => null,
                    ]);
            }
        }

        return redirect()->route('admin.survivors')->with('success', 'Survivor updated successfully.');
    }

    public function deleteSurvivor($id)
    {
        \DB::table('survivor')->where('id', $id)->delete(); // changed from 'survivors'

        return redirect()->route('admin.survivors')->with('success', 'Survivor deleted successfully!');
    }
}
