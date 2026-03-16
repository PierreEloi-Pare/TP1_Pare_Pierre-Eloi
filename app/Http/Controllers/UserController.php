<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Post(
        path: "/api/users",
        summary: "Créer un utilisateur",
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
            properties: [
                    new OA\Property(property: "firstName", type: "string", example: "MICHELLE"),
                    new OA\Property(property: "lastName", type: "string", example: "LE DEVOREUR DE PHP"),
                    new OA\Property(property: "email", type: "string", example: "email@test.com"),
                    new OA\Property(property: "phone", type: "string", example: "999-999-9999")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Utilisateur créé"),
            new OA\Response(response: 422, description: "Données invalides"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
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


    #[OA\Put(
        path: "/api/users/{id}",
        summary: "Modifier un utilisateur",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "User ID",
                in: "path",
                required: true
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "firstName", type: "string", example: "MICHELLE"),
                    new OA\Property(property: "lastName", type: "string", example: "LE DEVOREUR DE PHP"),
                    new OA\Property(property: "email", type: "string", example: "email@test.com"),
                    new OA\Property(property: "phone", type: "string", example: "999-999-9999")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Utilisateur modifié"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
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
