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
/*
//Default route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
//Initial route
Route::get('/', function () {
    return response()->json([
        'message' => 'GitHub social login implementation'
    ]);
});
/*
//Login with github
    //Route::post('/login', [GithubController::class, 'redirectToProvider']);

    // Alternative with Passport
    //Route::post('social/login', [GithubController::class, 'socialLogin']);

    //Other alternative
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
        Route::post('/login2', '\App\Http\Controllers\GithubController@login')->name('login2');
    });



//Show all users
Route::get('/users', [UserController::class, 'index']);

//Star a repository
Route::post('/repositories/{id}/star', [UserController::class, 'star']);*/

//SANCTUM
Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class,'handleProviderCallback']);
Route::get('/users', [UserController::class,'index']);


//Route::get('/star-repo/{user}/{repo}', [UserController::class,'addStarToRepo']);

Route::get('/star-repo/{owner}/{repo}', [UserController::class,'addStarToRepo']);