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
        return view('admin.survivors', compact('survivors'));
    }

    public function editSurvivor($id = null)
    {
        $survivor = $id === 'new' ? null : \DB::table('survivor')->where('id', $id)->first(); // changed from 'survivors'
        return view('admin.survivorsEdit', compact('survivor'));
    }

    public function storeSurvivor(Request $request)
    {
        \DB::table('survivor')->insert($request->except(['_token', '_method'])); // changed from 'survivors'

        return redirect()->route('admin.survivors')->with('success', 'Survivor added successfully!');
    }

    public function updateSurvivor(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        \DB::table('survivor')->where('id', $id)->update($data);
        return redirect()->route('admin.survivors')->with('success', 'Survivor updated!');
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
                  ->orWhere('location', 'LIKE', "%$search%")
                  ->orWhere('address', 'LIKE', "%$search%")
                  ->orWhere('unit', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%")
                  ->orWhere('total_beds', 'LIKE', "%$search%");
        }

        $ttus = $query->get();
        return view('admin.ttus', compact('ttus'));
    }
    public function ttusEdit($id = null)
    {
        $ttu = $id ? \DB::table('ttu')->where('id', $id)->first() : null;
        $locations = \DB::table('TTULocation')->pluck('loc_address', 'id');

        // Fetch survivor name if survivor_id is set
        $survivor_name = '';
        if ($ttu && $ttu->survivor_id) {
            $survivor = \DB::table('survivor')->where('id', $ttu->survivor_id)->first();
            if ($survivor) {
                $survivor_name = trim(($survivor->fname ?? '') . ' ' . ($survivor->lname ?? ''));
            }
        }

        // Optionally, fetch all survivors for a dropdown
        $survivors = \DB::table('survivor')->pluck(\DB::raw("CONCAT(fname, ' ', lname)"), 'id');

        return view('admin.ttusEdit', compact('ttu', 'locations', 'survivor_name', 'survivors'));
    }

    public function deleteTTU($id)
    {
        $ttu = \App\TTU::findOrFail($id); // Find the record or throw a 404 error
        $ttu->delete(); // Delete the record

        return redirect()->route('admin.ttus')->with('success', 'TTU record deleted successfully!');
    }

    public function storeTTU(Request $request)
    {
        $data = $request->except(['_token', '_method', 'survivor_name']);

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

        \App\TTU::create($data);
        return redirect()->route('admin.ttus')->with('success', 'TTU created!');
    }

    public function updateTTU(Request $request, $id)
    {
        $data = $request->except(['_token', '_method', 'survivor_name']);

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

        \App\TTU::where('id', $id)->update($data);
        return redirect()->route('admin.ttus')->with('success', 'TTU updated!');
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

}

