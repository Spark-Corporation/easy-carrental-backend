<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('active', true)->orderBy('created_at', 'desc')->paginate(15);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $data['photo'] = $path;
        }
        $user = User::create($data);
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)
            ->where('active', true)
            ->firstOrFail();
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $data['photo'] = $path;
        }
        $user->update($data);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->active = false;
        $user->save();
        return response()->Json(["message" => "utilisateur supprimé"], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user(); // récupère l’utilisateur connecté

        $validated = $request->validate([
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'string|nullable',
            'address' => 'string|nullable',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Validation avec messages personnalisés
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Veuillez saisir votre ancien mot de passe.',
            'new_password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas au nouveau mot de passe.',
        ]);

        // Vérifier que l'ancien mot de passe est correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'L’ancien mot de passe est incorrect.'
            ], 403);
        }

        // Mettre à jour avec le nouveau mot de passe
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès.'
        ]);
    }
}
