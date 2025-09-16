<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\CertificateType;
use App\Http\Requests\StoreCrewRequest;
use App\Http\Requests\UpdateCrewRequest;
use App\Models\CrewLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CrewController extends Controller
{
    public function index(Request $request)
    {
        $query = Crew::query();
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('crew_code', 'like', "%{$search}%");
            });
        }

        $crews = $query->orderBy('last_name')->paginate(15)->withQueryString();

        return Inertia::render('Crew/Index', [
            'crews' => $crews,
            'filters' => ['search' => $search],
        ]);
    }

    public function show(Crew $crew)
    {
        $crew->load(['certificates.type', 'certificates.vessel', 'certificates.files']);
        $types = CertificateType::orderBy('name')->get();
        $logs = \App\Models\CrewLog::where('crew_id', $crew->id)->latest()->take(50)->get();
        return Inertia::render('Crew/Show', [
            'crew' => $crew,
            'certificateTypes' => $types,
            'logs' => $logs,
        ]);
    }

    public function create()
    {
        return Inertia::render('Crew/Create');
    }

    public function store(StoreCrewRequest $request)
    {
        $crew = Crew::create($request->validated());
        CrewLog::create(['crew_id'=>$crew->id,'action'=>'created','meta'=>$request->validated()]);
        return redirect()->route('crew.show', $crew)->with('success','Crew created');
    }

    public function edit(Crew $crew)
    {
        return Inertia::render('Crew/Edit', ['crew'=>$crew]);
    }

    public function update(UpdateCrewRequest $request, Crew $crew)
    {
        $crew->update($request->validated());
        CrewLog::create(['crew_id'=>$crew->id,'action'=>'updated','meta'=>$request->validated()]);
        return back()->with('success','Crew updated');
    }

    public function destroy(Crew $crew)
    {
        CrewLog::create(['crew_id'=>$crew->id,'action'=>'deleted']);
        $crew->delete();
        return redirect()->route('crew.index')->with('success','Crew deleted');
    }
}
