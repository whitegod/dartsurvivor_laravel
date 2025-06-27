<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Get current and previous month (YYYY-MM)
        $currentMonth = now()->format('Y-m');
        $previousMonth = now()->subMonthNoOverflow()->format('Y-m');

        // TTUs created in the current month
        $monthlyTtus = DB::table('ttu')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->count();

        // TTUs created in the previous month
        $previousMonthlyTtus = DB::table('ttu')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$previousMonth])
            ->count();

        // Survivors created in the current month
        $monthlySurvivors = DB::table('survivor')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->count();

        // Survivors created in the previous month
        $previousMonthlySurvivors = DB::table('survivor')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$previousMonth])
            ->count();

        // Survivors where tpm is in the current month (Monthly Program Moveouts)
        $monthlyMoveouts = DB::table('survivor')
            ->whereNotNull('tpm')
            ->whereRaw("DATE_FORMAT(tpm, '%Y-%m') = ?", [$currentMonth])
            ->count();

        // Survivors where tpm is in the previous month
        $previousMonthlyMoveouts = DB::table('survivor')
            ->whereNotNull('tpm')
            ->whereRaw("DATE_FORMAT(tpm, '%Y-%m') = ?", [$previousMonth])
            ->count();

        // Calculate percent changes
        $ttusPercentChange = $previousMonthlyTtus > 0
            ? round((($monthlyTtus - $previousMonthlyTtus) / $previousMonthlyTtus) * 100, 1)
            : null;

        $survivorsPercentChange = $previousMonthlySurvivors > 0
            ? round((($monthlySurvivors - $previousMonthlySurvivors) / $previousMonthlySurvivors) * 100, 1)
            : null;

        $moveoutsPercentChange = $previousMonthlyMoveouts > 0
            ? round((($monthlyMoveouts - $previousMonthlyMoveouts) / $previousMonthlyMoveouts) * 100, 1)
            : null;

        $totalTtus = DB::table('ttu')->count();
        $totalSurvivors = DB::table('survivor')->count();

        // Get moveouts per month for the last 12 months
        $moveoutsPerMonth = DB::table('survivor')
            ->selectRaw("DATE_FORMAT(tpm, '%Y-%m') as month, COUNT(*) as count")
            ->whereNotNull('tpm')
            ->where('tpm', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $hotels = DB::table('hotel')
            ->select('id', 'name', 'address', DB::raw("'hotel' as type"))
            ->get()
            ->map(function($hotel) {
                // Count all rooms for this hotel
                $hotel->onsite = DB::table('room')
                    ->where('hotel_id', $hotel->id)
                    ->count();
                // Count assigned rooms (assuming 'assigned' column is boolean or status)
                $hotel->occupied = DB::table('room')
                    ->where('hotel_id', $hotel->id)
                    ->where('survivor_id', '!=', null)
                    ->count();
                return $hotel;
            });

        $stateparks = DB::table('statepark')
            ->select('id', 'name', 'address', DB::raw("'statepark' as type"))
            ->get()
            ->map(function($sp) {
                // Count all lodge_units for this statepark
                $sp->onsite = DB::table('lodge_unit')
                    ->where('statepark_id', $sp->id)
                    ->count();
                // Count assigned lodge_units (assuming 'assigned' column is boolean or status)
                $sp->occupied = DB::table('lodge_unit')
                    ->where('statepark_id', $sp->id)
                    ->where('survivor_id', '!=', null)
                    ->count();
                return $sp;
            });

        $privatesites = DB::table('privatesite')
            ->select('id', 'name', 'address', DB::raw("'privatesite' as type"))
            ->get()
            ->map(function($ps) {
                // Count all TTUs assigned to this privatesite
                $ps->onsite = DB::table('ttu')
                    ->where('privatesite', $ps->id)
                    ->count();
                // Count assigned TTUs (assuming 'assigned' column is boolean or status)
                $ps->occupied = DB::table('ttu')
                    ->where('privatesite', $ps->id)
                    ->where('survivor_id', '!=', null)
                    ->count();
                return $ps;
            });

        $allLocations = $hotels->merge($stateparks)->merge($privatesites);

        // Pass current month values to view
        return view('admin.dashboard', compact(
            'totalTtus',
            'ttusPercentChange',
            'totalSurvivors',
            'survivorsPercentChange',
            'monthlyMoveouts',
            'moveoutsPercentChange',
            'moveoutsPerMonth',
            'allLocations'
        ));
    }
}
