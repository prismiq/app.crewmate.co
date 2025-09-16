<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVesselRequest;
use App\Http\Requests\UpdateVesselRequest;
use App\Models\Vessel;
use Inertia\Inertia;

class VesselController extends Controller
{
    public function index()
    {
        return Inertia::render('Vessels/Index', [
            'vessels' => Vessel::orderBy('name')->paginate(15),
        ]);
    }

    public function store(StoreVesselRequest $request)
    {
        Vessel::create($request->validated());
        return back()->with('success', 'Vessel created');
    }

    public function update(UpdateVesselRequest $request, Vessel $vessel)
    {
        $vessel->update($request->validated());
        return back()->with('success', 'Vessel updated');
    }

    public function destroy(Vessel $vessel)
    {
        $vessel->delete();
        return back()->with('success', 'Vessel deleted');
    }
}
