<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Favorite;
use App\Models\Client;

class FavoriteController extends Controller
{
    public function MakeFavorite(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => "Vous n'êtes pas connecté !",
                ], 404);
            }

            if($user->id == $request->client_id_aimed) {
                return response()->json([
                    'message' => "Vous ne pouvez pas vous ajouter au favoris !",
                ], 404);
            }

            if(Client::where('id', $request->client_id_aimed)->doesntExist()) {
                return response()->json([
                    'message' => "Le profil que vous essayez d'ajouter aux favoris n'existe pas !",
                ], 404);
            }

            $favorite = Favorite::firstOrCreate([
                'client_id' => $user->id,
                'client_id_aimed' => $request->client_id_aimed,
            ]);

            return response()->json([
                'message' => "Profil ajouté aux favoris avec succès !",
                'data' => $favorite,
            ], 201);
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de l'ajout du profil aux favoris !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
