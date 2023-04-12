<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Default route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Initial route
Route::get('/', function () {
    return response()->json([
        'message' => 'GitHub social login implementation'
    ]);
});

//Login with github
    Route::post('/login', [GithubController::class, 'redirectToProvider']);


//Show all users
Route::get('/users', [UserController::class, 'index']);

//Star a repository
Route::post('/repositories/{id}/star', [UserController::class, 'star']);