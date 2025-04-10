<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Like;
use App\Models\Matching;
use App\Models\Client;

class LikeController extends Controller
{
    public function MakeLike(Request $request)
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
                    'message' => "Vous ne pouvez pas aimer votre propre profil !",
                ], 404);
            }

            if(Client::where('id', $request->client_id_aimed)->doesntExist()) {
                return response()->json([
                    'message' => "Le profil que vous essayez d'aimer n'existe pas !",
                ], 404);
            }

            $like = Like::firstOrCreate([
                'client_id' => $user->id,
                'client_id_aimed' => $request->client_id_aimed,
                'isLiked' => true
            ]);

            if(Like::where('client_id',  $request->client_id_aimed)->where('client_id_aimed', $user->id)->exists()) {
                $matching = Matching::firstOrCreate([
                    'client_id' => $user->id,
                    'client_id_other' => $request->client_id_aimed,
                ]);
                return response()->json([
                    'message' => "Félicitation vous avez un match !",
                    'data' => $matching,
                ], 201);
            }

            return response()->json([
                'message' => "Like envoyé avec succès !",
                'data' => $like,
            ], 201);
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur s'est produite lors du like !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function MakeDislike(Request $request)
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
                    'message' => "Vous ne pouvez pas disliker votre propre profil !",
                ], 404);
            }

            if(Client::where('id', $request->client_id_aimed)->doesntExist()) {
                return response()->json([
                    'message' => "Le profil que vous essayez de disliker n'existe pas !",
                ], 404);
            }

            $like = Like::firstOrCreate([
                'client_id' => $user->id,
                'client_id_aimed' => $request->client_id_aimed,
                'isLiked' => false
            ]);

            return response()->json([
                'message' => "Dislike éffecuté avec succès !",
                'data' => $like,
            ], 201);
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur s'est produite lors du dislike !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
