<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Historic;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    // Récupérer tous les paramètres
    public function index()
    {
        return response()->json(Setting::all());
    }

    // Récupérer un paramètre par clé
    public function show(String $key)
    {
        $value = Setting::get($key);
        return response()->json([
            'key' => $key,
            'value' => $value
        ]);
    }

    // Mettre à jour ou créer un paramètre
    public function update(Request $request, String $key)
    {
        $request->validate([
            'value' => 'required'
        ]);

        $setting = Setting::set($key, $request->value);

        //Historique
        Historic::create([
            'action'      => 'Mise à jour de paramètre',
            'description' => "Paramètre '{$key}' mis à jour avec la valeur '{$request->value}'",
            'user_id'     => Auth::id(),
            'actionDate'  => now(),
        ]);

        return response()->json([
            'message' => 'Paramètre mis à jour avec succès',
            'setting' => $setting
        ]);
    }
}
