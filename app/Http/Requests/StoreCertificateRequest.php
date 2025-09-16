<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crew_id' => ['required','exists:crews,id'],
            'vessel_id' => ['nullable','exists:vessels,id'],
            'certificate_type_id' => ['required','exists:certificate_types,id'],
            'reference' => ['nullable','string','max:255'],
            'issue_date' => ['nullable','date'],
            'expiry_date' => ['nullable','date','after_or_equal:issue_date'],
            'notes' => ['nullable','string'],
            'file' => ['required','file','mimes:pdf,jpg,jpeg,png','max:10240'],
        ];
    }
}
