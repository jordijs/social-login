<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

//Initial route
Route::get('/', function () {
    return response()->json([
        'message' => 'GitHub social login implementation'
    ]);
});

Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class,'handleProviderCallback']);
Route::get('/users', [UserController::class,'index']);
Route::get('/star-repo/{owner}/{repo}', [UserController::class,'addStarToRepo']);