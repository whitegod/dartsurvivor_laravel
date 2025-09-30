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

    public function store(Request $request)
    {
        $request->validate([
            'fdec' => 'required|string|max:255'
        ]);

        DB::table('fdec')->insert([
            'fdec_no' => $request->input('fdec')
        ]);

        return redirect()->route('admin.setting')->with('status', 'FDEC added.');
    }

    public function delete($id)
    {
        DB::table('fdec')->where('id', $id)->delete();
        return redirect()->route('admin.setting')->with('status', 'FDEC deleted.');
    }
}