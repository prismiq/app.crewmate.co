<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCrewRequest extends FormRequest
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
        $id = $this->route('crew')->id ?? null;
        return [
            'crew_code' => ['required','string','max:50','unique:crews,crew_code,'.($id ?? 'NULL').',id'],
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['nullable','email','unique:crews,email,'.($id ?? 'NULL').',id'],
            'phone' => ['nullable','string','max:255'],
            'rank' => ['nullable','string','max:255'],
            'nationality' => ['nullable','string','max:255'],
            'date_of_birth' => ['nullable','date'],
            'address_line1' => ['nullable','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'state' => ['nullable','string','max:255'],
            'postal_code' => ['nullable','string','max:255'],
            'country' => ['nullable','string','max:255'],
        ];
    }
}
