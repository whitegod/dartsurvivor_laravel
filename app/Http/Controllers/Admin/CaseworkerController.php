<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CaseworkerController extends Controller
{
    public function caseworkers(Request $request)
    {
        $query = DB::table('caseworker');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('fname', 'like', "%$search%")
                  ->orWhere('lname', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%");
            });
        }
        $caseworkers = $query->get();
        return view('admin.caseworkers', compact('caseworkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            // Add more validation rules as needed
        ]);

        DB::table('caseworker')->insert([
            'fname' => $request->fname,
            'lname' => $request->lname,
            // Add more fields as needed
        ]);

        return redirect()->back()->with('success', 'Caseworker added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
        ]);
        DB::table('caseworker')->where('id', $id)->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
        ]);
        return redirect()->back()->with('success', 'Caseworker updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('caseworker')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Caseworker deleted successfully!');
    }
}
