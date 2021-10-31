<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {   
        // $users = DB::table('users')->leftJoin('games', 'users.id', '=', 'games.user_id')->select('users.*', 'games.points')->orderByDesc('games.points')->get();
        // $users = DB::table('users')->leftJoin('games', 'users.id', '=', 'games.user_id')->select('users.*', 'games.points')->get();
        
        $users = User::withPointsCollection()->paginate(PER_PAGE);
        return UserCollection::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserStoreRequest  $request
     * 
     * @return \App\Http\Resources\UserResource
     */
    public function store(UserStoreRequest $request)
    {   
        $userId = 0;
        DB::transaction(function () use($request, &$userId){
            $userId = User::create($request->all())->id;
            Game::insert([
                'user_id'       => $userId,
                'points'        => 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        });

        $user = User::withPointsResource($userId)->first();
        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * 
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user): UserResource
    {
        $user = User::withPointsResource($user->id)->first();
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateScoreRequest  $request
     * @param  User  $user 
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(UpdateScoreRequest $request, User $user): AnonymousResourceCollection
    {
        $user = User::withPointsResource($user->id)->first();
 
        Game::when($request->operation === "addition", function ($query) use($user) {
            return $query->where('user_id', $user->id)->update(['points' => ($user->points + 1)]);
        }, function ($query) use($user) {
            return $query->where('user_id', $user->id)->update(['points' => ($user->points - 1)]);
        });

        $users = User::withPointsCollection()->paginate(PER_PAGE);
        return UserCollection::collection($users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * 
     * @return \App\Http\Resources\UserResource
     */
    public function destroy(User $user): UserResource
    {
        $user->delete();

        $deletedUser = User::withPointsResource($user->id)->withTrashed()->first();
        return new UserResource($deletedUser);
    }
}
