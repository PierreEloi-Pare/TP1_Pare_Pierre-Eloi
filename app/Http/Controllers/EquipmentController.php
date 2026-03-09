<?php

namespace App\Http\Controllers;

use App\Http\Requests\AveragePriceRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipmentController extends Controller
{
    public function index(){
        try{
            return EquipmentResource::collection(Equipment::all())->response()->setStatusCode(200);
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

    public function averagePrice(AveragePriceRequest $request, $id){
        try {
            Equipment::findOrFail($id);

            $query = Rental::whereNotNull('end_date');
            $query = $query->where('equipment_id', $id);

            if ($request->minDate) {
                $query->where('end_date', '>=', $request->minDate);
            }

            if ($request->maxDate) {
                $query->where('end_date', '<=', $request->maxDate);
            }

            if ($request->maxTotalPrice) {
                $query->where('totalPrice', '<=', $request->maxTotalPrice);
            }

            $avgPrice = $query->avg('totalPrice');

            $rentals = $query->paginate(20);

            return response()->json([
                'equipment_id' => $id,
                'average_price' => $avgPrice,
                'rentals' => $rentals
            ], 200);

        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
