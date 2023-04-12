<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')
        ->scopes(['read:user', 'public_repo'])
        ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function handleProviderCallback(Request $request)
    {
        $githubUser = Socialite::driver('github')->user();

        //If the GitHub User doesn't exist, creates it on database
        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'github_username' => $githubUser->nickname,
            'github_access_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);

        //Once we ensure that the user is on the database, we can login

        Auth::login($user);
        
        return response()->json([
            'message' => 'Login successful with user' .  $githubUser->nickname
        ], 200);
    }

    /**
     * Register a user using GitHub OAuth 2.0.
     */
    public function register(Request $request)
    {
        $user = Socialite::driver('github')->user();

        // Check if the user already exists in the database
        $existingUser = User::where('github_id', $user->getId())->first();

        if ($existingUser) {
            // User already exists
            return response()->json([
                'message' => 'User already registered'
            ], 400);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'github_id' => $user->getId(),
                'token' => $user->token,
                'avatar' => $user->getAvatar(),
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $newUser
            ]);
        }
    }
}

