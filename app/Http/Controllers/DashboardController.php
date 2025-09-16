<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $valid = Certificate::where('status', 'valid')->count();
        $expiring = Certificate::where('status', 'expiring')->count();
        $expired = Certificate::where('status', 'expired')->count();
        $flagged = Certificate::where('flagged', true)->count();

        return Inertia::render('Dashboard', [
            'stats' => [
                'valid' => $valid,
                'expiring' => $expiring,
                'expired' => $expired,
                'flagged' => $flagged,
            ],
        ]);
    }
}
