<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaseworkerController extends Controller
{
    public function caseworkers()
    {
        // Adjust the table/fields as needed for your DB structure
        $caseworkers = \DB::table('caseworker')->get();
        return view('admin.caseworkers', compact('caseworkers'));
    }
}
