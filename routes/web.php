<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//Redirect route to GitHub OAuth
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')
    ->scopes(['read:user', 'public_repo'])
    ->redirect();
});

// Receive the callback from GitHub after authentication
Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();
 
    //If the GitHub User doesn't exist, creates it on database
    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'github_username' => $githubUser->nickname,
        'github_access_token' => $githubUser->token,
        'github_refresh_token' => $githubUser->refreshToken,
    ]);
 
    Auth::login($user);
 
    return redirect('/dashboard');
});

