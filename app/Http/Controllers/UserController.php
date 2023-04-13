<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

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
    public function addStarToRepo(Request $request, $user, $repo)
    {
        $user = Socialite::driver('github')->userFromToken($token);
        // Add the repo to the user's favorites.
        $user->favorites()->create([
            'repo' => $repo,
        ]);
    }
}
