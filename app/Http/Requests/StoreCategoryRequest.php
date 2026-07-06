<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreCategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //'id'=>'required|int',
            'name'=>'required|string|unique:categories,name'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'=> 'le nom est obligatoire',
            'name.unique'=> 'le nom est unique',
        ];
    }



}
