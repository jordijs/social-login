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

        $githubUser = Socialite::driver('github')->stateless()->user();


        //If the GitHub User doesn't exist, creates it on database
        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'github_username' => $githubUser->nickname,
            'github_access_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);

        Auth::login($user, $remember = true);


        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
