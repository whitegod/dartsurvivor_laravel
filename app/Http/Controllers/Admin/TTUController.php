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
        // determine actual TTU table name from the model and get transfer columns
        $ttuTable = (new TTU)->getTable();
        // get transfer columns but exclude primary/key fields
        $transferCols = array_values(array_filter(\Schema::getColumnListing('transfer'), function($c){
            return $c !== 'id' && $c !== 'ttu_id';
        }));

        // select ttu table columns and explicit transfer columns (exclude transfer.id and transfer.ttu_id)
        $selects = [$ttuTable . '.*'];
        foreach ($transferCols as $col) {
            $selects[] = "transfer.{$col}";
        }
        $query = \App\TTU::leftJoin('transfer', $ttuTable . '.id', '=', 'transfer.ttu_id')
            ->select($selects);

        // apply FDEC filter (from URL ?fdec_id or ?fdec-filter, or cookie set by client-side script)
        $fdecFilter = $request->query('fdec_id')
            ?? $request->query('fdec-filter')
            ?? $request->cookie('fdecFilter')
            ?? null;

        if (!empty($fdecFilter)) {
            // allow comma-separated list in the query param
            $raw = is_array($fdecFilter) ? $fdecFilter : preg_split('/\s*,\s*/', trim((string)$fdecFilter));
            $ids = array_values(array_filter(array_map(function($v){ return trim((string)$v); }, (array)$raw)));

            try {
                $colType = \Schema::getColumnType($ttuTable, 'fdec_id');
            } catch (\Exception $e) {
                $colType = null;
            }

            if ($colType === 'json') {
                // build an OR group: match any of the provided fdec ids
                $query->where(function($q) use ($ttuTable, $ids) {
                    foreach ($ids as $id) {
                        if ($id === '') continue;
                        $q->orWhereJsonContains($ttuTable . '.fdec_id', (string)$id);
                    }
                });
            } else {
                // fallback for text column storing JSON: match any quoted id inside JSON string
                $query->where(function($q) use ($ttuTable, $ids) {
                    foreach ($ids as $id) {
                        if ($id === '') continue;
                        $q->orWhere($ttuTable . '.fdec_id', 'like', '%"' . (string)$id . '"%');
                    }
                });
            }
        }

        // Archived toggle: default to inbox (archived = 0) unless explicitly requested
        $showArchived = $request->query('archived') === '1' || $request->query('archived') === 1 || $request->query('archived') === 'true';
        try {
            if (\Schema::hasColumn($ttuTable, 'archived')) {
                $query->where($ttuTable . '.archived', $showArchived ? 1 : 0);
            }
        } catch (\Exception $e) {
            // schema introspection failed — fall back to not filtering
        }

        // apply search (prefix TTU columns)
        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('ttu.vin', 'LIKE', "%$s%")
                  ->orWhere('ttu.address', 'LIKE', "%$s%")
                  ->orWhere('ttu.unit', 'LIKE', "%$s%")
                  ->orWhere('ttu.status', 'LIKE', "%$s%")
                  ->orWhere('ttu.total_beds', 'LIKE', "%$s%");
            });
        }

        // default sort: TTU id ascending unless caller provided explicit sort params
        if (! $request->has('sort') && ! $request->has('order') && ! $request->has('order_by')) {
            $query->orderBy($ttuTable . '.id', 'asc');
        }

       
         $ttus = $query->get();
        // attach human-readable FDEC label(s) to each ttu
        $fdecMap = \DB::table('fdec')->pluck('fdec_no', 'id')->all();

        foreach ($ttus as $ttu) {
            $labels = [];
            $ids = $ttu->fdec_id ?? [];

            // normalize possible storage formats -> array of ids
            if (is_string($ids) && $ids !== '') {
                $decoded = json_decode($ids, true);
                $ids = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($ids)) {
                $ids = [];
            }

            foreach ($ids as $id) {
                if ($id === null || $id === '') continue;
                // prefer string key, fallback to int
                $labels[] = $fdecMap[$id] ?? $fdecMap[(int)$id] ?? (string)$id;
            }

          
            $ttu->fdec = $labels ? implode(', ', $labels) : '';
        }

        // perform post-processing and move transfer columns into a nested transfer object
        foreach ($ttus as $ttu) {
            // basic TTU transformations
            $ttu->author = $ttu->author
                ? (\DB::table('users')->where('id', $ttu->author)->value('name') ?? $ttu->author)
                : '';

            if (!empty($ttu->purchase_origin)) {
                $vendor = \DB::table('vendor')->where('id', $ttu->purchase_origin)->first();
                $ttu->purchase_origin = $vendor ? $vendor->name : $ttu->purchase_origin;
            }

            if (!empty($ttu->survivor_id)) {
                $survivor = \DB::table('survivor')->where('id', $ttu->survivor_id)->first();
                $ttu->survivor_id = $survivor
                    ? trim(($survivor->fname ?? '') . ' ' . ($survivor->lname ?? ''))
                    : $ttu->survivor_id;
            }

            // collect transfer columns (original names) from the joined row into $ttu->transfer
            $transferData = [];
            foreach ($transferCols as $c) {
                if (property_exists($ttu, $c) && $ttu->{$c} !== null) {
                    $transferData[$c] = $ttu->{$c};
                    // remove the flat property so TTU object stays clean
                    unset($ttu->{$c});
                }
            }

            // normalize recipient_type to 'yes'/'no' when it's stored as 1/0
            if (isset($transferData['recipient_type'])) {
                $rt = $transferData['recipient_type'];
                $rtLower = is_scalar($rt) ? strtolower((string)$rt) : '';
                if ($rtLower === '1' || $rtLower === 'true' || $rtLower === 'yes') {
                    $transferData['recipient_type'] = 'yes';
                } elseif ($rtLower === '0' || $rtLower === 'false' || $rtLower === 'no') {
                    $transferData['recipient_type'] = 'no';
                } else {
                    // leave non-boolean values as-is
                    $transferData['recipient_type'] = $rt;
                }
            }

            $ttu->transfer = $transferData;
        }

        // build fields list (TTU columns discovered from the model + transfer columns)
        $ttuFields = \Schema::getColumnListing($ttuTable);
        $fields = array_merge($ttuFields, $transferCols);

        // replace 'fdec_id' column with a display-only 'fdec' so views render the human label
        foreach ($fields as &$f) {
            if ($f === 'fdec_id') $f = 'fdec';
        }
        unset($f);

        // make filter + list available to the view so header select shows selection
        $fdecList = \DB::table('fdec')->orderBy('fdec_no')->get();
        return view('admin.ttus', compact('ttus', 'fields', 'fdecList', 'fdecFilter'));
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
        $is_being_donated = 0;
        $is_sold_at_auction = 0;
        if ($ttu) {
            $transfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();

            // transfer fields are stored as 'yes'/'no' varchar now
            if ($transfer) {
                $is_being_donated = (isset($transfer->being_donated) && strtolower($transfer->being_donated) === 'yes') ? 1 : 0;
                $is_sold_at_auction = (isset($transfer->sold_at_auction) && strtolower($transfer->sold_at_auction) === 'yes') ? 1 : 0;
            } else {
                // fallback to ttu-level fields (if present) which also use 'yes'/'no'
                $is_being_donated = (isset($ttu->being_donated) && strtolower($ttu->being_donated) === 'yes') ? 1 : 0;
                $is_sold_at_auction = (isset($ttu->sold_at_auction) && strtolower($ttu->sold_at_auction) === 'yes') ? 1 : 0;
            }
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

        $fdecList = \DB::table('fdec')->orderBy('fdec_no')->get();

        return view('admin.ttusEdit', compact(
            'ttu', 'privatesite', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer',
            'is_being_donated', 'is_sold_at_auction', 'fdecList'
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
        $is_being_donated = 0;
        $is_sold_at_auction = 0;
        if ($ttu) {
            $transfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();

            // transfer fields are stored as 'yes'/'no' varchar now
            if ($transfer) {
                $is_being_donated = (isset($transfer->being_donated) && strtolower($transfer->being_donated) === 'yes') ? 1 : 0;
                $is_sold_at_auction = (isset($transfer->sold_at_auction) && strtolower($transfer->sold_at_auction) === 'yes') ? 1 : 0;
            } else {
                // fallback to ttu-level fields (if present) which also use 'yes'/'no'
                $is_being_donated = (isset($ttu->being_donated) && strtolower($ttu->being_donated) === 'yes') ? 1 : 0;
                $is_sold_at_auction = (isset($ttu->sold_at_auction) && strtolower($ttu->sold_at_auction) === 'yes') ? 1 : 0;
            }
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

        $fdecList = \DB::table('fdec')->orderBy('fdec_no')->get();

        $readonly = true;
        return view('admin.ttusEdit', compact(
            'ttu', 'privatesite', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer',
            'is_being_donated', 'is_sold_at_auction', 'fdecList', 'readonly'
        ));
    }

    public function deleteTTU($id)
    {
        $ttu = TTU::find($id); 
        if ($ttu) {
            // Remove TTU reference from privatesite table
            \DB::table('privatesite')->where('ttu_id', $ttu->id)->update(['ttu_id' => null]);

            // Delete related record from transfer table
            \DB::table('transfer')->where('ttu_id', $ttu->id)->delete();

            // Delete the TTU record
            $ttu->delete();
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU record deleted successfully!');
    }

    public function archiveTTU(Request $request, $id)
    {
        $ttu = \App\TTU::find($id);
        if (!$ttu) {
            if ($request->ajax()) return response()->json(['error' => 'TTU not found'], 404);
            return redirect()->route('admin.ttus')->with('error', 'TTU not found');
        }

        // mark archived
        try {
            if (\Schema::hasColumn((new \App\TTU)->getTable(), 'archived')) {
                $ttu->archived = 1;
                $ttu->save();
            }
        } catch (\Exception $e) {
            // ignore if schema check fails
        }

        // detach related pointers: privatesite.ttu_id and transfer row; remove survivor assignment
        \DB::table('privatesite')->where('ttu_id', $ttu->id)->update(['ttu_id' => null]);
        \DB::table('transfer')->where('ttu_id', $ttu->id)->delete();
        $ttu->survivor_id = null;
        $ttu->li_date = null;
        $ttu->lo_date = null;
        $ttu->est_lo_date = null;
        $ttu->save();

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->route('admin.ttus')->with('success', 'TTU archived successfully.');
    }

    public function unarchiveTTU(Request $request, $id)
    {
        $ttu = \App\TTU::find($id);
        if (!$ttu) {
            if ($request->ajax()) return response()->json(['error' => 'TTU not found'], 404);
            return redirect()->route('admin.ttus')->with('error', 'TTU not found');
        }

        try {
            if (\Schema::hasColumn((new \App\TTU)->getTable(), 'archived')) {
                $ttu->archived = 0;
                $ttu->save();
            }
        } catch (\Exception $e) {
            // ignore
        }

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->route('admin.ttus')->with('success', 'TTU moved to inbox successfully.');
    }

    public function storeTTU(Request $request)
    {
        $data = $request->except([
            '_token', '_method', 'fema_id', 'survivor_name',
            // privatesite fields (do not store in TTU)
            'name', 'address', 'phone', 'pow', 'h2o', 'sew', 'own', 'res',
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite',
            // transfer fields
            'recipient_type', 'donation_agency', 'donation_category', 'sold_at_auction_price', 'recipient'
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
            $data[$field] = $request->input($field, 0);
        }

        $data['author'] = auth()->id();

        // store as array and let Eloquent handle JSON encoding via model casts
        $data['fdec_id'] = $request->input('fdec_id', []);
        $ttu = \App\TTU::create($data);

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
                'created_at' => now(),
            ];
            \DB::table('privatesite')->insert($privatesiteData);
        }

        // Save to transfer table if needed
        $is_being_donated = $request->input('is_being_donated', 0);
        $is_sold_at_auction = $request->input('is_sold_at_auction', 0);

        // always collect transfer fields from request (recipient_type included)
        // normalize recipient_type input: convert 1/0 or true/false to 'yes'/'no'
        $recipientTypeRaw = $request->input('recipient_type', null);
        $recipientType = null;
        if ($recipientTypeRaw !== null && $recipientTypeRaw !== '') {
            $rt = strtolower((string)$recipientTypeRaw);
            if ($rt === '1' || $rt === 'true' || $rt === 'yes') $recipientType = 'yes';
            elseif ($rt === '0' || $rt === 'false' || $rt === 'no') $recipientType = 'no';
            else $recipientType = $recipientTypeRaw;
        }

        $transferData = [
            'ttu_id' => $ttu->id,
            'recipient_type'       => $recipientType,
            'donation_agency'      => $request->input('donation_agency', null),
            'donation_category'    => $request->input('donation_category', null),
            'sold_at_auction_price'=> $request->input('sold_at_auction_price', null),
            'recipient'            => $request->input('recipient', null),
            'being_donated'        => $is_being_donated ? 'yes' : 'no',
            'sold_at_auction'      => $is_sold_at_auction ? 'yes' : 'no',
        ];

        // only insert when any transfer field or flags are present
        if ($request->filled('recipient_type') || $request->filled('donation_agency') || $request->filled('donation_category')
            || $request->filled('sold_at_auction_price') || $request->filled('recipient') || $is_being_donated || $is_sold_at_auction) {
            \DB::table('transfer')->insert($transferData);
        }

        // Add vendor if purchase origin is set
        $purchaseOrigin = $request->input('purchase_origin');
        if ($purchaseOrigin) {
            $vendor = \DB::table('vendor')->where('name', $purchaseOrigin)->first();
            if (!$vendor) {
                $vendorId = \DB::table('vendor')->insertGetId(['name' => $purchaseOrigin]);
            } else {
                $vendorId = $vendor->id;
            }
            $ttu->purchase_origin = $vendorId;
        } else {
            $ttu->purchase_origin = null;
        }

        // After saving $ttu, update survivor location_type if survivor_id is filled
        if ($request->filled('survivor_id')) {
            $survivor = \DB::table('survivor')->where('id', $request->input('survivor_id'))->first();
            if ($survivor) {
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
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite',
            // exclude transfer fields so we handle them separately
            'recipient_type', 'donation_agency', 'donation_category', 'sold_at_auction_price', 'recipient'
        ]);

        $data['survivor_id'] = $request->input('survivor_id');

        if ($request->location_type === 'privatesite') {
            $data['location'] = $request->input('name'); // Set location to privatesite name
        }

        $data['author'] = auth()->id();

        $purchaseOrigin = $request->input('purchase_origin');
        if ($purchaseOrigin) {
            $vendor = \DB::table('vendor')->where('name', $purchaseOrigin)->first();
            if (!$vendor) {
                $vendorId = \DB::table('vendor')->insertGetId(['name' => $purchaseOrigin]);
            } else {
                $vendorId = $vendor->id;
            }
            $ttu->purchase_origin = $vendorId;
        } else {
            $ttu->purchase_origin = null;
        }

        $ttu->unit_num = $request->input('unit_num');
        // store as array and let Eloquent handle JSON encoding via model casts
        $data['fdec_id'] = $request->input('fdec_id', []);
        $ttu->update($data);

        // If location_type is privatesite, update privatesite
        if ($request->location_type === 'privatesite') {
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

        // Save or update transfer table for donation/auction fields
        $is_being_donated = $request->input('is_being_donated', 0);
        $is_sold_at_auction = $request->input('is_sold_at_auction', 0);

        // normalize recipient_type input: convert 1/0 or true/false to 'yes'/'no'
        $recipientTypeRaw = $request->input('recipient_type', null);
        $recipientType = null;
        if ($recipientTypeRaw !== null && $recipientTypeRaw !== '') {
            $rt = strtolower((string)$recipientTypeRaw);
            if (in_array($rt, ['1','true','yes'], true)) $recipientType = 'yes';
            elseif (in_array($rt, ['0','false','no'], true)) $recipientType = 'no';
            else $recipientType = $recipientTypeRaw;
        }

        // always read transfer-related inputs and persist flags as 'yes'/'no'
        $transferData = [
            'recipient_type'        => $recipientType,
            'donation_agency'       => $request->input('donation_agency', null),
            'donation_category'     => $request->input('donation_category', null),
            'sold_at_auction_price' => $request->input('sold_at_auction_price', null),
            'recipient'             => $request->input('recipient', null),
            'being_donated'         => $is_being_donated ? 'yes' : 'no',
            'sold_at_auction'       => $is_sold_at_auction ? 'yes' : 'no',
        ];

        $existingTransfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();
        if ($existingTransfer) {
            // update existing row (overwrite with submitted values, including clearing fields)
            \DB::table('transfer')->where('ttu_id', $ttu->id)->update($transferData);
        } elseif (
            $request->filled('recipient_type') ||
            $request->filled('donation_agency') ||
            $request->filled('donation_category') ||
            $request->filled('sold_at_auction_price') ||
            $request->filled('recipient') ||
            $is_being_donated ||
            $is_sold_at_auction
        ) {
            // insert new transfer row only when any transfer data or flags present
            $transferData['ttu_id'] = $ttu->id;
            \DB::table('transfer')->insert($transferData);
        }

        // After updating $ttu, update survivor location_type if survivor_id is filled
        if ($request->filled('survivor_id')) {
            $survivor = \DB::table('survivor')->where('id', $request->input('survivor_id'))->first();
            if ($survivor) {
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
