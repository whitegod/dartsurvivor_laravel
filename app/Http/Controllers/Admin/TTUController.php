<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        // Fetch transfer data if exists
        $transfer = null;
        if ($ttu) {
            $transfer = \DB::table('transfer')->where('ttu_id', $ttu->id)->first();
        }

        // Optionally, fetch all survivors for a dropdown
        $survivors = \DB::table('survivor')->pluck(\DB::raw("CONCAT(fname, ' ', lname)"), 'id');

        $privatesite = null;
        if ($ttu) {
            $privatesite = \DB::table('privatesite')->where('survivor_id', $ttu->survivor_id)->first();
        }

        return view('admin.ttusEdit', compact(
            'ttu', 'privatesite', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer'
        ));
    }

    public function deleteTTU($id)
    {
        $ttu = \App\TTU::findOrFail($id); // Find the record or throw a 404 error
        $ttu->delete(); // Delete the record

        return redirect()->route('admin.ttus')->with('success', 'TTU record deleted successfully!');
    }

    public function storeTTU(Request $request)
    {
        $data = $request->except([
            '_token', '_method', 'fema_id', 'survivor_name',
            // privatesite fields (do not store in TTU)
            'roe', 'roi', 'pow', 'h2o', 'sew', 'own', 'res',
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite'
        ]);

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
        $ttu = \App\TTU::create($request->except([
            // exclude privatesite fields here
            'roe', 'roi', 'pow', 'h2o', 'sew', 'own', 'res',
            'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite'
        ]));

        // Save privatesite if privatesite switch is checked
        if ($request->has('privatesite')) {
            $privatesiteData = [
                'survivor_id' => $ttu->survivor_id,
                'roe' => $request->has('roe') ? 1 : 0,
                'roi' => $request->has('roi') ? 1 : 0,
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

        return redirect()->route('admin.ttus')->with('success', 'TTU created!');
    }

    public function updateTTU(Request $request, $id)
    {
        // $data = $request->except(['_token', '_method', 'fema_id', 'survivor_name']);
        $data = $request->except([
                '_token', '_method', 'fema_id', 'survivor_name',
                // privatesite fields (do not store in TTU)
                'roe', 'roi', 'pow', 'h2o', 'sew', 'own', 'res',
                'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response', 'privatesite'
            ]);
        // Remove transfer-only fields before saving TTU
        unset(
            $data['recipient_type'],
            $data['donation_agency'],
            $data['donation_category'],
            $data['sold_at_auction_price'],
            $data['recipient']
        );

        // Only keep donation fields if is_being_donated is checked
        if (empty($request->is_being_donated)) {
            unset($data['recipient_type'], $data['donation_agency'], $data['donation_category']);
        }
        
        if (empty($request->is_sold_at_auction)) {
            unset($data['sold_at_auction_price'], $data['recipient']);
        }

        // Map expect_lo_date to est_lo_date if present
        if (isset($data['expect_lo_date'])) {
            $data['est_lo_date'] = $data['expect_lo_date'];
            unset($data['expect_lo_date']);
        }

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
        \App\TTU::where('id', $id)->update($data);

        $ttu = \App\TTU::find($id);
        $survivor_id = $ttu->survivor_id;

        if ($request->has('privatesite')) {
            $privatesiteData = [
                'roe' => $request->has('roe') ? 1 : 0,
                'roi' => $request->has('roi') ? 1 : 0,
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
            if (\DB::table('privatesite')->where('survivor_id', $survivor_id)->exists()) {
                \DB::table('privatesite')->where('survivor_id', $survivor_id)->update($privatesiteData);
            } else {
                $privatesiteData['survivor_id'] = $survivor_id;
                \DB::table('privatesite')->insert($privatesiteData);
            }
        } else {
            \DB::table('privatesite')->where('survivor_id', $survivor_id)->delete();
        }

        // Save or update transfer table if needed (keep original logic)
        if ($request->is_being_donated || $request->is_sold_at_auction) {
            $transferData = [
                'ttu_id' => $id,
                'recipient_type' => $request->recipient_type,
                'donation_agency' => $request->donation_agency,
                'donation_category' => $request->donation_category,
                'sold_at_auction_price' => $request->sold_at_auction_price,
                'recipient' => $request->recipient,
                'donated' => $request->has('is_being_donated') ? 1 : 0,
                'auction' => $request->has('is_sold_at_auction') ? 1 : 0,
            ];
            if (\DB::table('transfer')->where('ttu_id', $id)->exists()) {
                \DB::table('transfer')->where('ttu_id', $id)->update($transferData);
            } else {
                \DB::table('transfer')->insert($transferData);
            }
        } else {
            // Optionally, remove transfer if neither is checked
            \DB::table('transfer')->where('ttu_id', $id)->delete();
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU updated!');
    }
}
