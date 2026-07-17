<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'lastname'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->where(fn ($query) =>
                    $query->where('firstname', $this->firstname)
                ),
            ],
            'firstname'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => 'required|string|min:8',
            'type'       => 'nullable|in:physique,morale',
            'pieceType'  => 'required|string|max:255',
            'pieceNumber' => 'required|string|max:50',
            'address'    => 'required|string|max:255',
            'photo'      => 'nullable|string|max:255',
            'phone'      => 'required|string|max:20',
            'active'     => 'boolean',
            'role'       => 'required|string|max:50',
        ];
    }
    public function messages(): array
    {
        return [
            'lastname.required'   => 'Le nom est obligatoire.',
            'lastname.unique'     => 'Ce utilisateur existe déjà.',
            'firstname.required'  => 'Le prénom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.email'         => 'L\'email doit être valide.',
            'email.unique'        => 'Cet email existe déjà.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 8 caractères.',
            'type.in'              => 'Le type doit être soit "physique" soit "morale".',
            'pieceType.required'  => 'Le type de pièce est obligatoire.',
            'pieceNumber.required' => 'Le numéro de pièce est obligatoire.',
            'address.required'    => 'L\'adresse est obligatoire.',
            'phone.required'      => 'Le numéro de téléphone est obligatoire.',
            'role.required'       => 'Le rôle est obligatoire.',
        ];
    }
}
