<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

use App\Mail\ClientMail;
use App\Models\User;
use App\Models\Client;
use App\Models\Image;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary as CloudinaryFacade;


class ClientController extends Controller
{

    public function GenerateCode(){
        try {    
            $code = random_int(1000, 9999);
            
            return $code;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Une erreur est survenue lors de la génération du code. Veuillez réessayer !', 'errors' => $th->getMessage()], 500);
        }
    }

    public function SendMailUser(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email'
            ]);
    
            $email = $data['email'];

            $code = $this->GenerateCode();
            $data['code'] = $code;
            $email = $data['email'];

            $subject = "Confirmation d'inscription via code de vérification";

            Mail::to($email)->send(new ClientMail($data, $subject));
    
            Cache::put("verification_code_$code", true, now()->addSeconds(60));

            return response()->json(['message' => 'E-mail envoyé avec succès !', 'data' => $code] , 200);

        } catch (\Exception $e) {
            return response()->json(["message" => "Échec de l'envoi. Veuillez vérifier votre connexion !", "errors" => $e->getMessage()], 500);
        }
    }
    
    public function ValidateCode(Request $request)
    {
        try {
            $data = $request->validate([
                'code' => 'required|integer'
            ]);
    
            $code = $data['code'];

            if (Cache::has("verification_code_$code")) {
                return response()->json(['message' => 'Code correcte !'], 200);
            }
            return response()->json(['message' => 'Code incorrecte ou expiré !'], 400);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erreur lors de la vérification du code !', 'errors' => $th->getMessage()], 500);
        }
    }

    # Authentification

    public function Register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $user = User::create([
                'name' => $request->name,
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $token = JWTAuth::fromUser($user);
    
            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'data' => ['user' => $user , 'token' => $token], 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la création de l'utilisateur !",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function Login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }

            return response()->json(['token' => $token]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la connexion !",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            
            $token = $request->bearerToken(); 

            if (!$token) {
                return response()->json([
                    'message' => 'Erreur lors de la déconnexion !',
                    'errors' => 'A token is requireds'
                ], 400);
            }

            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Déconnexion réussie']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la déconnexion !',
                'errors' => $e->getMessage()
            ], 500); 
        }
    }



    public function Me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'message' => "Vous n'êtes pas connecté !",
                ], 404);
            }

            $me = Client::with(['user', 'relations', 'images' , 'favorites' , 'likes', 'centerInterests' , 'matchings'])->where('user_id', $user->id)->first();

            if (!$me) {
                return response()->json([
                    'message' => "Aucun client trouvé !",
                ], 404);
            }

            return response()->json([
                'message' => "Utilisateur récupéré avec succès !",
                'data' => $me,
            ], 200);
        
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de la récupération de l'utilisateur !",
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    # Tokutana Services

    public function RegisterClient(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
                'name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'size' => 'required|numeric',
                'birth' => 'required|date',
                'gender' => 'required|string|max:40',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'interestBy' => 'required|string|max:40',
                'adress' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'centerInterests' => 'required|array',
                'relations' => 'required|array',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $client = Client::create([
                'user_id' => $user->id,
                'size' => $data['size'],
                'adress' => $data['adress'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'interestBy' => $data['interestBy'],
                'phone' => $data['phone'],
            ]);

            if ($data['image']) {
                $image = $data['image'];
                
                $uploadResult = CloudinaryFacade::uploadApi()->upload(
                    $image->getRealPath(),
                    [
                        'folder' => 'Tokutana',
                        'resource_type' => 'auto',
                    ]
                );

                $url = $uploadResult['secure_url'];

                $uploadedUrls[] = $url;

                Image::create([
                    'client_id' => $client->id,
                    'path' => $url,
                ]);
            }

            $client->relations()->attach($data['relations']);
            $client->centerInterests()->attach($data['centerInterests']);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'data' => ['user' => $user , 'client' => $client , 'token' => $token], 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Erreur lors de l'inscription !",
                'errors' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
                'error_type' => get_class($th)
            ], 500);
        }
    }

    public function Clients(){
        try {
            $clients = Client::with(['user', 'relations', 'centerInterests', 'images'])->latest()->take(15)->get();

            if ($clients->isEmpty()) {
                return response()->json([
                    "message" => "Aucun client trouvé !"
                ], 404);
            }

            return response()->json([
                "message" => "Clients récupérés avec succès !",
                "clients" => $clients
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la récupération des clients !",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function Client(Request $request, $id){
        try {
            $client = Client::with(['user', 'relations', 'centerInterests', 'images'])->find($id);

            if (!$client) {
                return response()->json([
                    "message" => "Client non trouvé !"
                ], 404);
            }

            return response()->json([
                "message" => "Clients récupérés avec succès !",
                "client" => $client
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la récupération des clients !",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function DeleteUser(){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    "message" => "Vous n'êtes pas connecté !"
                ], 404);
            }

            $user->delete();

            return response()->json([
                "message" => "Compte supprimé avec succès !"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Erreur lors de la suppression du client !",
                "errors" => $th->getMessage()
            ], 500);
        }
    }
}
