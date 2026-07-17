<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') instanceof \App\Models\User
        ? $this->route('user')->id
        : $this->route('user');

    return [
        'lastname' => [
            'required',
            'string',
            'max:255',
            Rule::unique('users')
                ->where(fn ($query) => $query->where('firstname', $this->input('firstname')))
                ->ignore($userId, 'id'),
        ],
        'firstname'  => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($userId, 'id'),
        ],
            'password'   => 'sometimes|nullable|string|min:8',
            'type'       => 'nullable|in:physique,morale',
            'pieceType'  => 'required|string|max:255',
            'pieceNumber' => 'required|string|max:50',
            'address'     => 'required|string|max:255',
            'photo'      => 'nullable|string|max:255',
            'phone'      => 'required|string|max:20',
            'active'     => 'nullable|boolean',
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
