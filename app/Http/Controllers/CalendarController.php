<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = (int)($request->input('year') ?? now()->year);
        $month = (int)($request->input('month') ?? now()->month);
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = (clone $start)->endOfMonth();

        $certs = Certificate::with(['crew:id,first_name,last_name', 'type:id,name'])
            ->whereBetween('expiry_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('expiry_date')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'date' => optional($c->expiry_date)->toDateString(),
                    'crew_id' => $c->crew_id,
                    'crew_name' => $c->crew?->full_name,
                    'type' => $c->type?->name,
                    'status' => $c->status,
                ];
            })
            ->groupBy('date');

        return Inertia::render('Certificates/Calendar', [
            'year' => $year,
            'month' => $month,
            'events' => $certs,
        ]);
    }
}
