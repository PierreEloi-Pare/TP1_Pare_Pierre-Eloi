<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;

class EquipmentController extends Controller
{
    #[OA\Get(
        path: "/api/equipments",
        summary: "Liste de tous les équipements",
        tags: ["Equipments"],
        responses: [
            new OA\Response(response: 200, description: "OK"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
    public function index(){
        try{
            $equipments = Equipment::paginate(20);

            return EquipmentResource::collection($equipments)->response()->setStatusCode(200);
        } catch (Exception $ex){
            abort(500, 'Internal Server Error');
        }
    }

    #[OA\Get(
        path: "/api/equipments/{id}",
        summary: "Afficher un seul équipement",
        tags: ["Equipments"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Equipment ID",
                in: "path",
                required: true
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "OK"),
            new OA\Response(response: 404, description: "Invalid id"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
    public function show($id){
        try{
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
        
    }

    #[OA\Get(
        path: "/api/equipments/{id}/popularity",
        summary: "Calculer la popularité d’un équipement",
        tags: ["Equipments"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Equipment ID",
                in: "path",
                required: true
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Popularité calculée"),
            new OA\Response(response: 404, description: "Invalid id"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
    public function popularity($id){
        try {
            $equipmentName = Equipment::findOrFail($id)->name;

            $locationsCount = Rental::where('equipment_id', $id)->count();

            $avgRating = Review::where('equipment_id', $id)->avg('rating') ?? 0;

            $popularity = ($locationsCount * 0.6) + ($avgRating * 0.4);

            // Utilisé LLM GPT 5 pour cet élément précis.
            // Requête demandée: "How do make a request that returns json and a status code response with a Laravel controller?"
            return response()->json([ 
                'equipment_id' => $id,
                'equipment_name' => $equipmentName,
                'popularity' => $popularity
            ], 200);

        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }

    #[OA\Get(
        path: "/api/equipments/{id}/average-price",
        summary: "Prix moyen de location d’un équipement",
        tags: ["Equipments"],
        parameters: [

            new OA\Parameter(
                name: "id",
                description: "Equipment ID",
                in: "path",
                required: true
            ),

            //https://swagger.io/docs/specification/v3_0/describing-parameters/#query-parameters

            new OA\Parameter(
                name: "minDate",
                description: "Date minimale",
                in: "query",
                required: false
            ),

            new OA\Parameter(
                name: "maxDate",
                description: "Date maximale",
                in: "query",
                required: false
            )

        ],
        responses: [
            new OA\Response(response: 200, description: "Prix moyen calculé"),
            new OA\Response(response: 404, description: "Invalid id"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
    public function averagePrice($id, UserRequest $request){
        try {
            Equipment::findOrFail($id);
            $request->validated();

            $query = Rental::whereNotNull('end_date'); 
            //J'ai pensé qu'il ne serait pas logique de chercher une location qui n'était pas fini parce que le prix n'est pas final? 
            //src: https://stackoverflow.com/questions/21281504/how-do-you-check-if-not-null-with-eloquent
            $query = $query->where('equipment_id', $id);

            //J'ai apris d'un collègue que l'erreur dans le pdf était seulement advenant la pagination, et que le filtre par date était toujours
            //pour le cas 7 (et non pour le cas 1) après la remise 2
            if ($request->minDate) {
                $query = $query->where('end_date', '>=', $request->minDate);
            }

            if ($request->maxDate) {
                $query = $query->where('end_date', '<=', $request->maxDate);
            }

            $avgPrice = $query->avg('total_price');

            return response()->json([
                'equipment_id' => $id,
                'average_price' => $avgPrice,
            ], 200);

        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
