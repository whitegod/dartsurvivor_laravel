<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function fdec()
    {
        // Get all rows from the fdec table
        $fdecs = DB::table('fdec')->get();
        return view('admin.setting', compact('fdecs'));
    }
}