<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipmentController extends Controller
{
    public function index(UserRequest $request){
        try{
            $request->validated();
            $equipments = Equipment::paginate(20);

             if ($request->minDate) {
                $equipments->where('end_date', '>=', $request->minDate);
            }

            if ($request->maxDate) {
                $equipments->where('end_date', '<=', $request->maxDate);
            }
            return EquipmentResource::collection($equipments)->response()->setStatusCode(200);
        } catch (Exception $ex){
            abort(500, 'Internal Server Error');
        }
    }

    public function show($id){
        try{
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(200);
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch(Exception $ex){
            abort(500, 'Server error');
        }
        
    }

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

    public function averagePrice($id){
        try {
            Equipment::findOrFail($id);

            $query = Rental::whereNotNull('end_date'); 
            //J'ai pensé qu'il ne serait pas logique de chercher une location qui n'était pas fini parce que le prix n'est pas final? 
            //src: https://stackoverflow.com/questions/21281504/how-do-you-check-if-not-null-with-eloquent
            $query = $query->where('equipment_id', $id);

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
