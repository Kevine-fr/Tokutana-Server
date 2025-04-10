<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Relation;

class RelationController extends Controller
{
    public function Relations(){
        try {
            $relations = Relation::all();

            if($relations->isEmpty()) {
                return response()->json([
                    'message' => "Aucune relation trouvée !",
                ], 404);
            }
            return response()->json([
                'message' => "Relations récupérées avec succès !",
                'data' => $relations,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de la récupération des relations !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
