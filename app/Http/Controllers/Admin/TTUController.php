<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Privatesite; 
use App\TTU;

class TTUController extends Controller
{
    public function ttus(Request $request)
    {
        $query = \App\TTU::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('vin', 'LIKE', "%$search%")
                  ->orWhere('address', 'LIKE', "%$search%")
                  ->orWhere('unit', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%")
                  ->orWhere('total_beds', 'LIKE', "%$search%");
        }

        $ttus = $query->get();
        $fields = \Schema::getColumnListing('ttu');
        return view('admin.ttus', compact('ttus', 'fields'));
    }

    public function ttusEdit($id = null)
    {
        $ttu = $id ? \DB::table('ttu')->where('id', $id)->first() : null;
        $locations = \DB::table('ttulocation')->pluck('loc_name', 'id');

        // Fetch survivor name and FEMA-ID if survivor_id is set
        $survivor_name = '';
        $selectedFemaId = '';
        if ($ttu && $ttu->survivor_id) {
            $survivor = \DB::table('survivor')->where('id', $ttu->survivor_id)->first();
            if ($survivor) {
                $survivor_name = trim(($survivor->fname ?? '') . ' ' . ($survivor->lname ?? ''));
                $selectedFemaId = $survivor->fema_id ?? '';
            }
        }

        // Fetch author name if available
        $authorName = '';
        if ($ttu && $ttu->author) {
            $author = \DB::table('users')->where('id', $ttu->author)->first();
            if ($author) {
                $authorName = trim(($author->name ?? $author->username ?? $author->email ?? ''));
            }
        }

        // When passing $ttu to the view
        if ($ttu && $ttu->purchase_origin) {
            $vendor = \DB::table('vendor')->where('id', $ttu->purchase_origin)->first();
            if ($vendor) {
                $ttu->purchase_origin = $vendor->name;
            }
        }

        // Fetch transfer data if exists
        $transfer = null;
        if ($ttu) {
            $transfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();
        }

        // Optionally, fetch all survivors for a dropdown
        $survivors = \DB::table('survivor')->pluck(\DB::raw("CONCAT(fname, ' ', lname)"), 'id');

        // Fetch privatesite with created_at
        $privatesite = null;
        if ($ttu) {
            $privatesite = \DB::table('privatesite')
                ->select('*', 'created_at')
                ->where('ttu_id', $ttu->id)
                ->first();
        }

        return view('admin.ttusEdit', compact(
            'ttu', 'privatesite', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer'
        ));
    }

    public function ttusView($id)
    {
        $ttu = \DB::table('ttu')->where('id', $id)->first();
        $locations = \DB::table('ttulocation')->pluck('loc_name', 'id');

        // Fetch survivor name and FEMA-ID if survivor_id is set
        $survivor_name = '';
        $selectedFemaId = '';
        if ($ttu && $ttu->survivor_id) {
            $survivor = \DB::table('survivor')->where('id', $ttu->survivor_id)->first();
            if ($survivor) {
                $survivor_name = trim(($survivor->fname ?? '') . ' ' . ($survivor->lname ?? ''));
                $selectedFemaId = $survivor->fema_id ?? '';
            }
        }

        // When passing $ttu to the view
        if ($ttu && $ttu->purchase_origin) {
            $vendor = \DB::table('vendor')->where('id', $ttu->purchase_origin)->first();
            if ($vendor) {
                $ttu->purchase_origin = $vendor->name;
            }
        }

        // Fetch author name if available
        $authorName = '';
        if ($ttu && $ttu->author) {
            $author = \DB::table('users')->where('id', $ttu->author)->first();
            if ($author) {
                $authorName = trim(($author->name ?? $author->username ?? $author->email ?? ''));
            }
        }

        // Fetch transfer data if exists
        $transfer = null;
        if ($ttu) {
            $transfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();
        }

        // Optionally, fetch all survivors for a dropdown
        $survivors = \DB::table('survivor')->pluck(\DB::raw("CONCAT(fname, ' ', lname)"), 'id');

        // Fetch privatesite with created_at
        $privatesite = null;
        if ($ttu) {
            $privatesite = \DB::table('privatesite')
                ->select('*', 'created_at')
                ->where('ttu_id', $ttu->id)
                ->first();
        }

        $readonly = true;
        return view('admin.ttusEdit', compact(
            'ttu', 'privatesite', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer', 'readonly'
        ));
    }

    public function deleteTTU($id)
    {
        $ttu = TTU::find($id); 
        if ($ttu) {
            // Remove TTU reference from privatesite table
            \DB::table('privatesite')->where('ttu_id', $ttu->id)->update(['ttu_id' => null]);

            // Remove TTU reference from transfer table
            \DB::table('transfer')->where('ttu_id', $ttu->id)->update(['ttu_id' => null]);

            // Delete the TTU record
            $ttu->delete();
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU record deleted successfully!');
    }

    public function storeTTU(Request $request)
    {
        $data = $request->except([
            '_token', '_method', 'fema_id', 'survivor_name',
            // privatesite fields (do not store in TTU)
            'name', 'address', 'phone', 'pow', 'h2o', 'sew', 'own', 'res',
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite'
        ]);

        $data['survivor_id'] = $request->input('survivor_id');

        // Set checkboxes to 0 if not checked
        $featureFields = [
            'has_200sqft', 'has_propanefire', 'has_tv', 'has_hydraul',
            'has_steps', 'has_teardrop', 'has_foldwalls', 'has_extkitchen'
        ];
        $statusFields = [
            'is_onsite', 'is_occupied', 'is_winterized', 'is_deblocked',
            'is_cleaned', 'is_gps_removed', 'is_being_donated', 'is_sold_at_auction'
        ];
        foreach (array_merge($featureFields, $statusFields) as $field) {
            $data[$field] = $request->has($field) ? 1 : 0;
        }

        $data['author'] = auth()->id();
        $ttu = \App\TTU::create($data); // <-- use $data, not $request->except([...])

        // Save privatesite if privatesite switch is checked
        if ($request->has('privatesite')) {
            $privatesiteData = [
                'ttu_id' => $ttu->id,
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
                'created_at' => now(), // <-- add created_at for insert
            ];
            \DB::table('privatesite')->insert($privatesiteData);
        }

        // Save to transfer table if needed (keep original logic)
        if ($request->is_being_donated || $request->is_sold_at_auction) {
            $transferData = [
                'ttu_id' => $ttu->id,
                'recipient_type' => $request->recipient_type,
                'donation_agency' => $request->donation_agency,
                'donation_category' => $request->donation_category,
                'sold_at_auction_price' => $request->sold_at_auction_price,
                'recipient' => $request->recipient,
                'donated' => $request->has('is_being_donated') ? 1 : 0,
                'auction' => $request->has('is_sold_at_auction') ? 1 : 0,
            ];
            \DB::table('transfer')->insert($transferData);
        }

        // Add vendor if purchase origin is set
        $purchaseOrigin = $request->input('purchase_origin');
        if ($purchaseOrigin) {
            // Try to find vendor by name
            $vendor = \DB::table('vendor')->where('name', $purchaseOrigin)->first();
            if (!$vendor) {
                // Insert new vendor and get its ID
                $vendorId = \DB::table('vendor')->insertGetId(['name' => $purchaseOrigin]);
            } else {
                $vendorId = $vendor->id;
            }
            // Save vendor ID to TTU
            $ttu->purchase_origin = $vendorId;
        } else {
            $ttu->purchase_origin = null;
        }

        // After saving $ttu, update survivor location_type if survivor_id is filled
        if ($request->filled('survivor_id')) {
            $survivor = \DB::table('survivor')->where('id', $request->input('survivor_id'))->first();
            if ($survivor) {
                // Decode location_type as array, or start new array
                $types = [];
                if (!empty($survivor->location_type)) {
                    $types = @json_decode($survivor->location_type, true);
                    if (!is_array($types)) $types = [];
                }
                if (!in_array('TTU', $types)) {
                    $types[] = 'TTU';
                    \DB::table('survivor')->where('id', $survivor->id)
                        ->update(['location_type' => json_encode($types)]);
                }
            }
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU created!');
    }

    public function updateTTU(Request $request, $id)
    {
        $ttu = \App\TTU::findOrFail($id);

        // If privatesite, set location to privatesite name
        $data = $request->except([
            'survivor_name',
            'name', 'address', 'phone', 'pow', 'h2o', 'sew', 'own', 'res',
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite'
        ]);

        $data['survivor_id'] = $request->input('survivor_id');

        if ($request->location_type === 'privatesite') {
            $data['location'] = $request->input('name'); // Set location to privatesite name
        }

        $purchaseOrigin = $request->input('purchase_origin');
        if ($purchaseOrigin) {
            // Try to find vendor by name
            $vendor = \DB::table('vendor')->where('name', $purchaseOrigin)->first();
            if (!$vendor) {
                // Insert new vendor and get its ID
                $vendorId = \DB::table('vendor')->insertGetId(['name' => $purchaseOrigin]);
            } else {
                $vendorId = $vendor->id;
            }
            // Save vendor ID to TTU
            $ttu->purchase_origin = $vendorId;
        } else {
            $ttu->purchase_origin = null;
        }

        $ttu->fill($data);
        $ttu->unit_num = $request->input('unit_num');
        $ttu->save();

        // If location_type is privatesite, update privatesite
        if ($request->location_type === 'privatesite') {
            // Always fetch privatesite by TTU id
            $privatesite = Privatesite::where('ttu_id', $ttu->id)->first();
            if (!$privatesite) {
                $privatesite = new Privatesite();
                $privatesite->ttu_id = $ttu->id;
            }
            $privatesite->name = $request->input('name');
            $privatesite->address = $request->input('address');
            $privatesite->phone = $request->input('phone');
            $privatesite->damage_assessment = $request->input('damage_assessment');
            $privatesite->ehp = $request->input('ehp');
            $privatesite->ehp_notes = $request->input('ehp_notes');
            $privatesite->dow_long = $request->input('dow_long');
            $privatesite->dow_lat = $request->input('dow_lat');
            $privatesite->zon = $request->input('zon');
            $privatesite->dow_response = $request->input('dow_response');
            $privatesite->pow = $request->has('pow');
            $privatesite->h2o = $request->has('h2o');
            $privatesite->sew = $request->has('sew');
            $privatesite->own = $request->has('own');
            $privatesite->res = $request->has('res');
            $privatesite->save();

        }

        // After updating $ttu, update survivor location_type if survivor_id is filled
        if ($request->filled('survivor_id')) {
            $survivor = \DB::table('survivor')->where('id', $request->input('survivor_id'))->first();
            if ($survivor) {
                // Decode location_type as array, or start new array
                $types = [];
                if (!empty($survivor->location_type)) {
                    $types = @json_decode($survivor->location_type, true);
                    if (!is_array($types)) $types = [];
                }
                if (!in_array('TTU', $types)) {
                    $types[] = 'TTU';
                    \DB::table('survivor')->where('id', $survivor->id)
                        ->update(['location_type' => json_encode($types)]);
                }
            }
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU updated!');
    }

    public function locationSuggestions(Request $request)
    {
        $type = $request->input('type');
        $query = $request->input('query', '');

        if ($type === 'hotel') {
            $results = DB::table('hotel')
                ->where('name', 'like', "%$query%")
                ->pluck('name');
        } elseif ($type === 'statepark') {
            $results = DB::table('statepark')
                ->where('name', 'like', "%$query%")
                ->pluck('name');
        } elseif ($type === 'privatesite') {
            $results = DB::table('privatesite')
                ->where('name', 'like', "%$query%")
                ->pluck('name');
        } else {
            $results = collect();
        }

        return response()->json($results);
    }

    public function getPrivatesiteByTTU($ttu_id)
    {
        $ttu = \App\TTU::find($ttu_id);
        if (!$ttu || !$ttu->location_id) {
            return response()->json(['success' => false]);
        }
        $privatesite = \DB::table('privatesite')->where('id', $ttu->location_id)->first();
        return response()->json(['success' => true, 'privatesite' => $privatesite]);
    }

    public function unitSuggestions(Request $request)
    {
        $stateparkName = $request->input('statepark');
        $query = $request->input('query');

        // Get statepark id by name
        $statepark = \DB::table('statepark')->where('name', $stateparkName)->first();
        if (!$statepark) {
            return response()->json([]);
        }

        // Get unit names for this statepark
        $units = \DB::table('lodge_unit')
            ->where('statepark_id', $statepark->id)
            ->where('unit_name', 'like', '%' . $query . '%')
            ->pluck('unit_name')
            ->unique()
            ->values();

        return response()->json($units);
    }

    public function vendorSuggestions(Request $request)
    {
        $query = $request->input('query');
        $vendors = \DB::table('vendor')
            ->where('name', 'like', '%' . $query . '%')
            ->pluck('name')
            ->unique()
            ->values();
        return response()->json($vendors);
    }

    public function femaSuggestions(Request $request) {
        $query = $request->input('query');
        $results = \DB::table('survivor')
            ->where('fema_id', 'like', "%$query%")
            ->orWhere(\DB::raw("CONCAT(fname, ' ', lname)"), 'like', "%$query%")
            ->select('id', 'fema_id', \DB::raw("CONCAT(fname, ' ', lname) as name"))
            ->get();
        return response()->json($results);
    }
}
