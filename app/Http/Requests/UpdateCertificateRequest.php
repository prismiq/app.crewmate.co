<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
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
            'vessel_id' => ['nullable','exists:vessels,id'],
            'reference' => ['nullable','string','max:255'],
            'issue_date' => ['nullable','date'],
            'expiry_date' => ['nullable','date','after_or_equal:issue_date'],
            'notes' => ['nullable','string'],
            'file' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:10240'],
            'status' => ['nullable','in:valid,expiring,expired,flagged'],
            'flagged' => ['nullable','boolean'],
        ];
    }
}
