<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{


    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {

        return Socialite::driver($provider)->scopes(['read:user', 'public_repo'])->stateless()->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {

        /*
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'github_id' => $user->getId()
            ],
            [
                //'email_verified_at' => now(),
                'github_username' => $user->getNickname(),
                'github_access_token' => $user->token,
                'github_refresh_token' => $user->refreshToken,
                //'status' => true,
            ]
        );
      
        //$token = $userCreated->createToken('token-name')->plainTextToken;

        */

        
            $githubUser = Socialite::driver('github')->stateless()->user();

            return $githubUser;
            }

    public function getToken($githubUser){
        $token = $githubUser->token;
        return $token;
    }

            
    public function storeUser($githubUser){
            
            //If the GitHub User doesn't exist, creates it on database
            $user = User::updateOrCreate([
                'github_id' => $githubUser->id,
            ], [
                'github_username' => $githubUser->nickname,
                'github_access_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
         
            Auth::login($user);


            return response()->json([
                'success' => true,  
                'user' => $user
            ]);
    }



}