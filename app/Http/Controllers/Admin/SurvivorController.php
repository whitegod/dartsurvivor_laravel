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

        if ($survivor && $survivor->location_type === 'TTU') {
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
        }
        if ($survivor && $survivor->location_type === 'Hotel') {
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $hotel = \App\Hotel::find($room->hotel_id);
                $hotelName = $hotel ? $hotel->name : '';
                $hotelRoom = $room->room_num;
                $hotelLiDate = $room->li_date;
                $hotelLoDate = $room->lo_date;
            }
        }
        if ($survivor && $survivor->location_type === 'State Park') {
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
            'readonly'
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

        if ($survivor && $survivor->location_type === 'TTU') {
            $ttu = \App\TTU::where('survivor_id', $survivor->id)->first();
        }

        if ($survivor && $survivor->location_type === 'Hotel') {
            $room = \App\Room::where('survivor_id', $survivor->id)->first();
            if ($room) {
                $hotel = \App\Hotel::find($room->hotel_id);
                $hotelName = $hotel ? $hotel->name : '';
                $hotelRoom = $room->room_num;
                $hotelLiDate = $room->li_date;
                $hotelLoDate = $room->lo_date;
            }
        }

        // ADD THIS BLOCK FOR STATE PARK
        if ($survivor && $survivor->location_type === 'State Park') {
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
            'stateparkName', 'unitName', 'stateparkLiDate', 'stateparkLoDate'
        ));
    }

    public function storeSurvivor(Request $request)
    {
        $data = $request->except([
            'hotel_name', 'hotel_room', 'hotel_li_date', 'hotel_lo_date',
            'statepark_name', 'statepark_site', 'statepark_li_date', 'statepark_lo_date'
        ]);

        // Set authored to current user name
        $data['author'] = auth()->user()->name ?? 'Unknown';

        $survivor = Survivor::create($data);

        if ($request->location_type === 'Hotel') {
            // Save hotel (create if not exists)
            $hotel = \App\Hotel::firstOrCreate(
                ['name' => $request->hotel_name],
                ['name' => $request->hotel_name]
            );

            // Save room (create or update)
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
        }

        if ($request->location_type === 'TTU' && $request->vin) {
            $ttu = \App\TTU::where('vin', $request->vin)->first();
            if ($ttu) {
                $ttu->lo = $request->lo;
                $ttu->lo_date = $request->lo_date;
                $ttu->est_lo_date = $request->est_lo_date;
                $ttu->survivor_id = $survivor->id; // Link TTU to this survivor
                $ttu->save();
            }
        }

        if ($request->location_type === 'State Park') {
            // Find the state park row
            $parkRow = \DB::table('statepark')->where('name', $request->statepark_name)->first();
            if ($parkRow) {
                // Find the lodge_unit row for this park and site
                $unit = \DB::table('lodge_unit')
                    ->where('statepark_id', $parkRow->id)
                    ->where('unit_name', $request->unit_name)
                    ->first();

                if ($unit) {
                    // Assign survivor_id and dates to the unit
                    \DB::table('lodge_unit')
                        ->where('id', $unit->id)
                        ->update([
                            'survivor_id' => $survivor->id,
                            'li_date' => $request->statepark_li_date,
                            'lo_date' => $request->statepark_lo_date,
                        ]);
                }
            }
        }

        return redirect()->route('admin.survivors')->with('success', 'Survivor added successfully!');
    }

    public function updateSurvivor(Request $request, $id)
    {
        $data = $request->except([
            'vin', 'lo', 'lo_date', 'est_lo_date',
            'hotel_name', 'hotel_room', 'hotel_li_date', 'hotel_lo_date',
            'statepark_name', 'statepark_site', 'statepark_li_date', 'statepark_lo_date'
        ]);

        // Set author to current user name
        $data['author'] = auth()->user()->name ?? 'Unknown';

        $survivor = Survivor::findOrFail($id);
        $survivor->update($data);

        if ($request->location_type === 'Hotel') {
            $hotel = \App\Hotel::firstOrCreate(
                ['name' => $request->hotel_name],
                ['name' => $request->hotel_name]
            );

            // Find the old room assigned to this survivor (if any)
            $oldRoom = \App\Room::where('survivor_id', $survivor->id)->first();

            // If editing, clear survivor_id, li_date, and lo_date from previous room if room number changed
            if ($oldRoom && $oldRoom->room_num !== $request->hotel_room) {
                $oldRoom->survivor_id = null;
                $oldRoom->li_date = null;
                $oldRoom->lo_date = null;
                $oldRoom->save();
            }

            // Assign survivor_id and dates to the selected room
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
        }

        // Update TTU row if location_type is TTU and VIN is present
        if ($request->location_type === 'TTU' && $request->vin) {
            $ttu = \App\TTU::where('vin', $request->vin)->first();
            if ($ttu) {
                $ttu->lo = $request->lo;
                $ttu->lo_date = $request->lo_date;
                $ttu->est_lo_date = $request->est_lo_date;
                $ttu->survivor_id = $survivor->id;
                $ttu->save();
            }
        }

        if ($request->location_type === 'State Park') {
            // Find the state park row
            $parkRow = \DB::table('statepark')->where('name', $request->statepark_name)->first();
            if ($parkRow) {
                // Find the lodge_unit row for this park and site
                $unit = \DB::table('lodge_unit')
                    ->where('statepark_id', $parkRow->id)
                    ->where('unit_name', $request->unit_name)
                    ->first();

                if ($unit) {
                    // Assign survivor_id and dates to the unit
                    \DB::table('lodge_unit')
                        ->where('id', $unit->id)
                        ->update([
                            'survivor_id' => $survivor->id,
                            'li_date' => $request->statepark_li_date,
                            'lo_date' => $request->statepark_lo_date,
                        ]);
                }
            }
        }

        // (Optional) Handle hotel and state park updates here as well

        // Redirect or return as needed
        return redirect()->route('admin.survivors')->with('success', 'Survivor updated successfully.');
    }

    public function deleteSurvivor($id)
    {
        \DB::table('survivor')->where('id', $id)->delete(); // changed from 'survivors'

        return redirect()->route('admin.survivors')->with('success', 'Survivor deleted successfully!');
    }
}
