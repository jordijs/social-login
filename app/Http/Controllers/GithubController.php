<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Passport\Passport;

class GithubController extends Controller
{

/**
 * Social Login
 */
public function socialLogin(Request $request)
{
    $token = $request->input('access_token');
    // get the github's user. (In the github server)
    $githubUser = Socialite::driver('github')->userFromToken($token);
    // check if access token exists etc..
    // search for a user in our server with the specified github id 
    $user = User::where('github_id', $githubUser->id)->first();
    // if there is no record with these data, create a new user
    if($user == null){
        $user = User::create([
            'github_id' => $githubUser->id,
            'github_username' => $githubUser->nickname,
            'github_access_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);
    }
    // create a token for the user, so they can login
    $token = $user->createToken(env('APP_NAME'))->accessToken;
    // return the token for usage
    return response()->json([
        'success' => true,
        'token' => $token
    ]);
}




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


    public function login(Request $request)
    {
        dd($request);
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $oauthToken = $user->createToken('MyApp')->accessToken;
            return response()->json(['oauth_token' => $oauthToken], 200);
        } else {
            return response()->json(['error' => 'Invalid login details'], 401);
        }
    }
}

