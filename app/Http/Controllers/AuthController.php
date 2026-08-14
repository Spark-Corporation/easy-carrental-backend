<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Historic;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register (StoreUserRequest $request)
    {
        $user = $request->validated();
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $user['photo'] = $path;
        }
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'utilisateur créé avec succès',
            'user' => $user,
            'role' => $user->role,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
        ], 201);
    }
//login de l'admin
    public function loginAdmin(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    $user = Auth::user();

    // Vérification du rôle attendu
    if ($user->role !== 'admin') {
        Auth::logout();
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    Historic::create([
        'user_id'       => $user->id,
        'action'      => 'login',
        'actionDate' => now(),
    ]);

    return response()->json([
        'message'      => 'Connexion réussie',
        'access_token' => $token,
        'user'         => new UserResource($user),
        'role'         => $user->role,
        'token_type'   => 'Bearer',
    ]);
}

// login du client
public function loginClient(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    $user = Auth::user();

    // Vérification du rôle attendu
    if ($user->role !== 'client') {
        Auth::logout();
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    Historic::create([
        'user_id'       => $user->id,
        'action'      => 'login',
        'actionDate' => now(),
    ]);

    return response()->json([
        'message'      => 'Connexion réussie',
        'access_token' => $token,
        'user'         => new UserResource($user),
        'role'         => $user->role,
        'token_type'   => 'Bearer',
    ]);
}




    public function logout(Request $request)
    {
        //$request->user()->currentAccessToken()->delete();
        optional($request->user()->currentAccessToken())->delete();
        // Enregistrement de l'historique de déconnexion
        Historic::create([
            'user_id' => $request->user()->id,
            'action' => 'logout',
            'actionDate' => now()
        ]);

        return response()->json(['message' => 'deconnexion réussie'], 200);
    }
}
