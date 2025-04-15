<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\RelationClientController;

# Routes init
Route::get('/', function () {
    return "Hello API !";
});
Route::get('/login', function () {
    return response()->json(['message' => "Vous n'êtes pas autorisé à éffectuer cette action !", 'errors' => 'Token invalide ou manquant'], 401);
})->name('login');



Route::post('/users/login', [ClientController::class, 'Login']);
Route::post('/users/register', [ClientController::class, 'Register']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/me', [ClientController::class, 'Me']);
    Route::post('/users/logout', [ClientController::class, 'logout']);
});


Route::controller(ClientController::class)->group(function () {
    Route::post('/register-user', 'RegisterUser');
    Route::post('/send-mail-user', 'SendMailUser');
    Route::post('/validate-code', 'ValidateCode');
    Route::get('/test-access', 'TestAccess')->middleware('auth:sanctum');
});

Route::post('/users/register-client', [ClientController::class, 'RegisterClient']);
Route::get('/users/clients', [ClientController::class, 'Clients']);
Route::get('/users/client/{id}', [ClientController::class, 'Client']);
Route::delete('/users/delete', [ClientController::class, 'DeleteUser'])->middleware('jwt.auth');


Route::get('/files/latest', [ImageController::class, 'LatestFiles']);

Route::post('/make-favorite', [FavoriteController::class, 'MakeFavorite'])->middleware('jwt.auth');

Route::post('/make-like', [LikeController::class, 'MakeLike'])->middleware('jwt.auth');
Route::post('/make-dislike', [LikeController::class, 'MakeDislike'])->middleware('jwt.auth');

Route::get('/relations', [RelationController::class, 'Relations']);

Route::post('/add-relation-client', [RelationClientController::class, 'AddRelationClient'])->middleware('jwt.auth');


Route::post('/upload', [ImageController::class, 'Upload'])->middleware('jwt.auth');



