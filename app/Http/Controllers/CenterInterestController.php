<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CenterInterest;

class CenterInterestController extends Controller
{
     public function CenterInterests(){
        try {
            $CI = CenterInterest::latest()->take(15)->get();

            if($CI->isEmpty()) {
                return response()->json([
                    'message' => "Aucune relation trouvée !",
                ], 404);
            }
            return response()->json([
                'message' => "Centres d'intérets récupérées avec succès !",
                'data' => $CI,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de la récupération des relations !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
