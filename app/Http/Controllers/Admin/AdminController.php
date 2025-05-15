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

    public function detailedSearch()
    {
        $data=[];
        return view('admin.detailedSearch',$data);
    }

    public function reporting()
    {
        $data=[];
        return view('admin.reporting',$data);
    }

    public function survivors()
    {
        $data=[];
        return view('admin.survivors',$data);
    }

    public function ttus()
    {
        $ttus = \App\TTU::all(); // Fetch all TTU records
        return view('admin.ttus', compact('ttus'));
    }
    public function ttusEdit()
    {
        $data=[];
        return view('admin.ttusEdit',$data);
    }

    public function userPermissions()
    {
        $data=[];
        return view('admin.user_permissions',$data);
    }

}

