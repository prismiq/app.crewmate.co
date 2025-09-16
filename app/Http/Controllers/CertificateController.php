<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Models\Certificate;
use App\Models\CertificateFile;
use App\Models\CrewLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function store(StoreCertificateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $certificate = Certificate::create([
            'crew_id' => $data['crew_id'],
            'vessel_id' => $data['vessel_id'] ?? null,
            'certificate_type_id' => $data['certificate_type_id'],
            'reference' => $data['reference'] ?? null,
            'issue_date' => $data['issue_date'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'status' => 'valid',
            'notes' => $data['notes'] ?? null,
        ]);

        $certificate->status = $certificate->computeStatus();
        $certificate->save();
        $certificate->load('type');

        $file = $request->file('file');
        $path = $file->store("certificates/{$certificate->crew_id}", 'public');
        CertificateFile::create([
            'certificate_id' => $certificate->id,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        CrewLog::create(['crew_id'=>$certificate->crew_id,'action'=>'certificate_uploaded','meta'=>[
            'certificate_id'=>$certificate->id,
            'type_id'=>$certificate->certificate_type_id,
            'type_name'=>$certificate->type?->name,
            'expiry_date'=>$certificate->expiry_date?->toDateString(),
        ]]);

        return back()->with('success', 'Certificate uploaded successfully.');
    }

    public function update(UpdateCertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $certificate->fill($request->validated());
        $certificate->status = $certificate->computeStatus();
        $certificate->save();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store("certificates/{$certificate->crew_id}", 'public');
            CertificateFile::create([
                'certificate_id' => $certificate->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Certificate updated.');
    }

    public function download(CertificateFile $file)
    {
        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }
}
