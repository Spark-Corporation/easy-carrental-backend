<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePanneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'panneAmount' => 'required|double',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=> 'le nom est obligatoire',
            'panneAmount.required'=> 'le prix de la reparation doit etre renséigné',
            'panneAmount.double'=> 'le prix dois etre un double',
        ];
    }
}
