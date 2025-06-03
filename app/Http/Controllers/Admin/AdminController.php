<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Input;
use App\HolidayDefault;
use App\Admins;
use App\User;
use App\Role;
use App\Globals;
use App\Survivor;
use App\Room;
use Auth;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SendLinkResetPassword; 
use App\Workers;
use App\Clients;
use App\TimeCards;
use Illuminate\Support\Facades\Mail;
use App\Mail\TimecardEndedWorker;
use App\ClientInfoWorkers;
use App\TimeSheets;
use App\Mail\BillingcycleEndedAccountmanager;

class AdminController extends Controller
{
    

    public function create()
    {
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
 
        $data=[
            'status'=>$status,
        ];

        return view('admin.admin.create',$data);
    }
    public function store(Request $request)
    { 
        $data=$request->all();
        if (count(User::where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.admin.create');
        }
        if (count(Admins::where('email',$data['email'])->get())>0){
            Session::flash('message',"Admin email already exists on other Admin. Please input unique email for new Admin.");
            return redirect()->route('admin.admin.create');
        }

        $admin=Admins::create();
        $admin->status=$data['status'];
        $admin->first_name=$data['first_name'];
        $admin->last_name=$data['last_name'];
        $admin->fullname=$data['first_name'].' '.$data['last_name'];
        $admin->email=$data['email'];
        $admin->phone=$data['phone'];
        $admin->address1=$data['address1'];
        $admin->address2=$data['address2'];
        $admin->city=$data['city'];
        $admin->state=$data['state'];
        $admin->zip=$data['zip'];
        
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();

        $user= User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['user_email'],
            'password' => bcrypt($data['password']),
        ]);
        $default_role= Role::where('name', 'Admin')->first();
        $user->attachRole($default_role);
        $admin->user_id=$user->id;
        $admin->save();

        Session::flash('message',"New Admin: ".$admin->fullname." created successfully.");
        return redirect()->route('admin.admin.profile',$admin->id);

    }
    public function profile($id)
    {
        $admin=Admins::where('id',$id)->first();
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();
        $status=[
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];

        $data=[
            'status'=>$status,
            'admin'=>$admin,
            
        ];
        return view('admin.admin.profile',$data);
    }

    public function update(Request $request)
    { 
        $data=$request->all();
        $admin=Admins::where('id',$data['admin_id'])->first();

        if (count(User::where('id','!=',$admin->user_id)->where('email',$data['user_email'])->get())>0){
            Session::flash('message',"User email already exists. Please input unique user email.");
            return redirect()->route('admin.admin.profile',$admin->id);
        }
        if (count(Admins::where('id','!=',$admin->id)->where('email',$data['email'])->get())>0){
            Session::flash('message',"Admin email already exists on other Admin. Please input unique email for this Admin.");
            return redirect()->route('admin.admin.profile',$admin->id);
        }

        $admin->status=$data['status'];
        $admin->first_name=$data['first_name'];
        $admin->last_name=$data['last_name'];
        $admin->fullname=$data['first_name'].' '.$data['last_name'];
        $admin->email=$data['email'];
        $admin->phone=$data['phone'];
        $admin->address1=$data['address1'];
        $admin->address2=$data['address2'];
        $admin->city=$data['city'];
        $admin->state=$data['state'];
        $admin->zip=$data['zip'];
        $admin->updated_at=date('Y-m-d H:i:s');
        $admin->save();
         
        $user=$admin->user();
        $user->name=$data['first_name'].' '.$data['last_name'];
        $user->email=$data['user_email'];
        $user->password=bcrypt($data['password']);
        $user->save();

        Session::flash('message',"Administrator: ".$admin->fullname."'s profile updated.");
        return redirect()->route('admin.admin.profile',$admin->id);

    }

    public function search()
    {
        $status=[
            'all'=>'All',
            'active'=>'Active',
            'inactive'=>'Inactive',
        ];
        
        $admins=Admins::where('deleted_at',null);
        if (session()->has('admin_search.status')){
            if (session('admin_search.status')!='all'){
                $admins=$admins->where('status',session('admin_search.status'));
            }
        }
        
        if (session()->has('admin_search.first_name')){
            if (session('admin_search.first_name')!=''){
                $admins=$admins->where('first_name','like','%'.session('admin_search.first_name').'%');
            }
        }
        if (session()->has('admin_search.last_name')){
            if (session('admin_search.last_name')!=''){
                $admins=$admins->where('last_name','like','%'.session('admin_search.last_name').'%');
            }
        }
        if (session()->has('admin_search.email')){
            if (session('admin_search.email')!=''){
                $admins=$admins->where('email','like','%'.session('admin_search.email').'%');
            }
        }

        if (session()->has('admin_search.phone')){
            if (session('admin_search.phone')!=''){
                $admins=$admins->where('phone','like','%'.session('admin_search.phone').'%');
            }
        }
        
        if (session()->has('admin_search.city')){
            if (session('admin_search.city')!=''){
                $admins=$admins->where('city','like','%'.session('admin_search.city').'%');
            }
        }
        if (session()->has('admin_search.state')){
            if (session('admin_search.state')!=''){
                $admins=$admins->where('state','like','%'.session('admin_search.state').'%');
            }
        }
        if (session()->has('admin_search.zip')){
            if (session('admin_search.zip')!=''){
                $admins=$admins->where('zip','like','%'.session('admin_search.zip').'%');
            }
        }

        if (session()->has('admin_search.address1')){
            if (session('admin_search.address1')!=''){
                $admins=$admins->where('address1','like','%'.session('admin_search.address1').'%');
            }
        }
        if (session()->has('admin_search.address2')){
            if (session('admin_search.address2')!=''){
                $admins=$admins->where('address2','like','%'.session('admin_search.address2').'%');
            }
        }
  
        $admins=$admins->orderBy('last_name')->get();
 
        $data=[
            'status'=>$status,
            'admins'=>$admins,
             
        ];

        return view('admin.admin.search',$data);
    }
    public function setfilter(Request $request)
    {
        session(['admin_search.status'=>$request->status]);
        session(['admin_search.first_name'=>$request->first_name]);
        session(['admin_search.last_name'=>$request->last_name]);
        session(['admin_search.email'=>$request->email]);
        session(['admin_search.phone'=>$request->phone]);
        session(['admin_search.city'=>$request->city]);
        session(['admin_search.state'=>$request->state]);
        session(['admin_search.zip'=>$request->zip]);
        session(['admin_search.address1'=>$request->address1]);
        session(['admin_search.address2'=>$request->address2]);
        return redirect()->route('admin.admin.search');
    }
    public function resetfilter(Request $request)
    {
        session()->forget('admin_search');
        return redirect()->route('admin.admin.search');
    }

    public function remove(Request $request)
    {
        $admin=Admins::where('id',$request->admin_id)->first();
        $fullname=$admin->fullname;
        $admin->user()->delete();
        $admin->delete();
        Session::flash('message',"Administrator: ".$fullname." removed.");
        return redirect()->route('admin.admin.search');
    }
 

    


    public function createPayrollManager()
    {
        return view('admin.Admin_createPayrollManager');
    }
    public function searchPayrollManager()
    {
        return view('admin.Admin_searchPayrollManager');
    }   
    public function profilePayrollManager()
    {
        return view('admin.Admin_profilePayrollManager');
    } 

    public function dashboard()
    {
        $data=[];
        return view('admin.dashboard',$data);
    }

    public function detailedSearch(Request $request)
    {
        $query = \DB::table('author');

        // Determine which field to group by
        $countBy = $request->input('countBy', 'author'); // default to 'author'
        $groupField = $countBy === 'address' ? 'address' : 'author_name';

        // Keyword filter (optional, keep if you want)
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('author_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('address', 'like', '%' . $request->keyword . '%');
            });
        }

        // Text filter by selected field
        if ($request->filled('text') && $request->filled('search_by_field')) {
            $field = $request->input('search_by_field') === 'author' ? 'author_name' : 'address';
            $query->where($field, 'like', '%' . $request->input('text') . '%');
        }

        // Group and count by selected field
        $results = $query->select("$groupField as group_value")
            ->selectRaw('COUNT(*) as count')
            ->groupBy($groupField)
            ->get();

        // For display
        foreach ($results as $row) {
            if ($countBy === 'author') {
                $row->author = $row->group_value;
                $row->address = '';
            } else {
                $row->author = '';
                $row->address = $row->group_value;
            }
        }

        return view('admin.detailedSearch', compact('results', 'countBy'));
    }

    public function reporting()
    {
        $data=[];
        return view('admin.reporting',$data);
    }

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

        return view('admin.ttusEdit', compact(
            'ttu', 'locations', 'survivor_name', 'survivors', 'selectedFemaId', 'authorName', 'transfer'
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
        $data = $request->except(['_token', '_method', 'fema_id', 'survivor_name']);

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

        $data['author'] = auth()->id(); // or Auth::id() if not using helper
        $ttu = \App\TTU::create($data);

        // Save to transfer table if needed
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
        $data = $request->except(['_token', '_method', 'fema_id', 'survivor_name']);

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

        $data['author'] = auth()->id(); // Only if you want to update the author on edit
        \App\TTU::where('id', $id)->update($data);

        // Save or update transfer table if needed
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
        }

        return redirect()->route('admin.ttus')->with('success', 'TTU updated!');
    }

        public function locations()
    {
        // Fetch hotels with survivor hh_size sum
        $hotels = \DB::table('hotel')
            ->select(
                'hotel.id', // <-- Add this line!
                \DB::raw("'Hotel' as type"),
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
                \DB::raw("'State Park' as type"),
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

        // Merge both collections and sort by created_at descending
        $locations = $hotels->merge($stateparks)->sortByDesc('created_at')->values();

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

    public function userPermissions()
    {
        $users = \App\User::with('roles')->get(); // Fetch users with their roles
        return view('admin.user_permissions', compact('users'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = \App\User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'status' => 1,
            'records_authored' => 0,
        ]);
        $user->roles()->attach($request->role_id);

        return redirect()->route('admin.user_permissions')->with('success', 'User added successfully!');
    }

    public function removeUser($id)
    {
        $user = \App\User::findOrFail($id);

        // Detach all roles from the user (removes from role_user table)
        $user->roles()->detach();

        // Now delete the user
        $user->delete();

        return redirect()->route('admin.user_permissions')->with('success', 'User removed successfully!');
    }

    public function reactivateUser($id)
    {
        $user = \App\User::findOrFail($id);
        $user->status = 1;
        $user->save();

        return redirect()->route('admin.user_permissions')->with('success', 'User reactivated successfully!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('admin.user_permissions')->with('success', 'Password reset successfully!');
    }

    public function locationEdit(Request $request)
    {
        $id = $request->query('id');
        $type = $request->query('type');
        $location = null;
        $rooms = null;
        $lodge_units = null;

        if ($id && $type === 'Hotel') {
            $location = \DB::table('hotel')->where('id', $id)->first();
            $rooms = \DB::table('room')
                ->leftJoin('survivor', 'room.survivor_id', '=', 'survivor.id')
                ->select('room.room_num', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('room.hotel_id', $id)
                ->get()
                ->map(function($r) {
                    $r->survivor_name = $r->fname ? $r->fname . ' ' . $r->lname : null;
                    return $r;
                });
        } elseif ($id && $type === 'State Park') {
            $location = \DB::table('statepark')->where('id', $id)->first();
            $lodge_units = \DB::table('lodge_unit')
                ->leftJoin('survivor', 'lodge_unit.survivor_id', '=', 'survivor.id')
                ->select('lodge_unit.unit_name', 'survivor.fname', 'survivor.lname', 'survivor.hh_size')
                ->where('lodge_unit.statepark_id', $id)
                ->get()
                ->map(function($u) {
                    $u->survivor_name = $u->fname ? $u->fname . ' ' . $u->lname : null;
                    return $u;
                });
        }

        return view('admin.locationsEdit', compact('location', 'type', 'rooms', 'lodge_units'));
    }

    public function locationUpdate(Request $request, $id)
    {
        $type = $request->input('type');
        $data = $request->only(['name', 'address', 'phone']);
        $data['updated_at'] = now();
        $data['author'] = auth()->user()->name ?? 'Unknown'; 

        if ($type === 'Hotel') {
            \DB::table('hotel')->where('id', $id)->update($data);
        } elseif ($type === 'State Park') {
            \DB::table('statepark')->where('id', $id)->update($data);
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

        if ($type === 'Hotel') {
            \DB::table('hotel')->insert($data);
        } elseif ($type === 'State Park') {
            \DB::table('statepark')->insert($data);
        }
        return redirect()->route('admin.locations')->with('success', 'Location added!');
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
        ]);

        \DB::table('lodge_unit')->insert([
            'statepark_id' => $validated['location_id'],
            'unit_name' => $validated['number'],
        ]);

        return redirect()->back()->with('success', 'Lodge Unit added successfully!');
    }

    public function deleteLocation($id, Request $request)
    {
        $type = $request->query('type');
        \Log::info("DeleteLocation called for id=$id, type=$type");

        if ($type === 'Hotel') {
            \DB::table('room')->where('hotel_id', $id)->delete();
            \DB::table('hotel')->where('id', $id)->delete();
        } elseif ($type === 'State Park') {
            \DB::table('lodge_unit')->where('statepark_id', $id)->delete();
            \DB::table('statepark')->where('id', $id)->delete();
        }
        return redirect()->route('admin.locations')->with('success', 'Location and related units deleted!');
    }

    public function caseworkers()
    {
        // Adjust the table/fields as needed for your DB structure
        $caseworkers = \DB::table('caseworker')->get();
        return view('admin.caseworkers', compact('caseworkers'));
    }
}

