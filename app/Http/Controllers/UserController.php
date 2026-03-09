<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        try {
                $user = User::create($request->validated());
                return (new UserResource($user))->response()->setStatusCode(201);
            } catch (QueryException $ex) {
                abort(422, 'Cannot be created in database');
            } catch (Exception $ex) {
                abort(500, 'Internal Server Error');
        }
    }

    public function update(UpdateUserRequest $request, $id){
        try{
            $user = User::findOrFail($id);
            $user->update($request->validated());
            return (new UserResource($user))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
        
    }
}
