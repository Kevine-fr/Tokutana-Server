<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\RelationClient;
use App\Models\Relation;

class RelationClientController extends Controller
{
    public function AddRelationClient(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'message' => "Vous n'êtes pas connecté !",
                ], 404);
            }

            if(Relation::where('id', $request->relation_id)->doesntExist()) {
                return response()->json([
                    'message' => "La relation que vous essayez d'ajouter n'existe pas !",
                ], 404);
            }

            $relation = RelationClient::firstOrCreate([
                'client_id' => $user->id,
                'relation_id' => $request->relation_id,
            ]);

            return response()->json([
                'message' => "Relation ajoutée avec succès !",
                'data' => $relation,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de l'ajout de la relation !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
