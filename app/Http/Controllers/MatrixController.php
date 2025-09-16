<?php

namespace App\Http\Controllers;

use App\Models\{Crew, CertificateType, Certificate, Vessel};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MatrixController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'crew' => $request->input('crew'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'vessel' => $request->input('vessel'),
        ];

        $crewsQuery = Crew::query();
        if ($filters['search']) {
            $s = $filters['search'];
            $crewsQuery->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('crew_code', 'like', "%$s%");
            });
        }
        if ($filters['crew']) {
            $ids = is_array($filters['crew']) ? $filters['crew'] : [$filters['crew']];
            $crewsQuery->whereIn('id', $ids);
        }
        $crews = $crewsQuery->with(['certificates' => function($q) use ($filters){
            if ($filters['type']) $q->whereIn('certificate_type_id', (array)$filters['type']);
            if ($filters['status']) $q->whereIn('status', (array)$filters['status']);
            if ($filters['vessel']) $q->whereIn('vessel_id', (array)$filters['vessel']);
        }, 'certificates.type'])->orderBy('last_name')->get();

        return Inertia::render('Certificates/Matrix', [
            'crews' => $crews,
            'types' => CertificateType::orderBy('name')->get(),
            'vessels' => Vessel::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        // Reuse index logic for filtering but flatten to CSV
        $crews = Crew::with(['certificates.type'])->orderBy('last_name')->get();
        $types = CertificateType::orderBy('name')->get();
        $headers = array_merge(['Crew Member'], $types->pluck('name')->toArray());

        $callback = function() use ($crews, $types, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($crews as $c) {
                $row = [$c->full_name];
                foreach ($types as $t) {
                    $cert = $c->certificates->firstWhere('certificate_type_id', $t->id);
                    $row[] = $cert?->status ?? 'missing';
                }
                fputcsv($out, $row);
            }
            fclose($out);
        };
        return response()->streamDownload($callback, 'certificate_matrix.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
