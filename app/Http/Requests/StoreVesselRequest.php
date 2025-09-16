<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVesselRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // authorize all authenticated users; tighten later with policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'imo_number' => ['nullable','string','max:255','unique:vessels,imo_number'],
            'flag' => ['nullable','string','max:255'],
            'owner' => ['nullable','string','max:255'],
        ];
    }
}
