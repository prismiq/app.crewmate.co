<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVesselRequest extends FormRequest
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
        $id = $this->route('vessel')->id ?? null;
        return [
            'name' => ['required','string','max:255'],
            'imo_number' => ['nullable','string','max:255','unique:vessels,imo_number,'.($id ?? 'NULL').',id'],
            'flag' => ['nullable','string','max:255'],
            'owner' => ['nullable','string','max:255'],
        ];
    }
}
