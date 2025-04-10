<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\Client;
use App\Models\Image;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary as CloudinaryFacade;

class ImageController extends Controller
{
    public function Upload(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => "Vous n'êtes pas connecté !",
                ], 404);
            }

            if($user->client() == null) {
                return response()->json([
                    'message' => "Vous n'avez pas de compte client !",
                ], 404);
            }

            $request->validate([
                'files' => 'required|array',
                'files.*' => 'file'
            ]);

            $uploadedUrls = [];

            foreach ($request->file('files') as $file) {
                $uploadResult = CloudinaryFacade::uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'Tokutana',
                        'resource_type' => 'auto',
                    ]
                );

                $url = $uploadResult['secure_url'];

                $uploadedUrls[] = $url;

                Image::create([
                    'client_id' => $user->client->id,
                    'path' => $url,
                ]);
            }
            return response()->json([
                'message' => "Fichiers uploadés avec succès !",
                'data' => $uploadedUrls,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de l'upload !",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    public function LatestFiles(){
        try {
            $files = Image::latest()->take(15)->get();

            if ($files->isEmpty()) {
                return response()->json([
                    "message" => "Aucun fichier trouvé !"
                ], 404);
            }

            return response()->json($files);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la récupération des fichiers !",
                "errors" => $th->getMessage()
            ], 500);
        }   
    }

}
