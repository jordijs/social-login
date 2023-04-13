<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::all();

        return response([
            'users' =>
            UserResource::collection($users),
            'message' => 'Successful'
        ], 200);
    }

    /**
     * Give a star to a repo
     */
    public function addStarToRepo(Request $request, $owner, $repo)
    {   

        $user = User::orderBy('updated_at', 'DESC')->first();
        
        //Get the token of the user
        $token = $user->github_access_token;

        //Transform token for github api call

        $tokenAddress = 'Bearer ' . $token;


        // Call the api of github
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.github.com/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $address = 'user/starred/' . $owner . '/' . $repo;
        $response = $client->put($address, [
            'headers' => [
                'Accept' => 'application/vnd.github+json',
                'Authorization' => $tokenAddress,
                'X-GitHub-Api-Version' => '2022-11-28'
            ],
        ]);


    }
}
