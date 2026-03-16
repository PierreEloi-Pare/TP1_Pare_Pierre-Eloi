<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Delete(
        path: "/api/reviews/{id}",
        summary: "Supprimer un avis",
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Review ID",
                in: "path",
                required: true
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Avis supprimé"),
            new OA\Response(response: 404, description: "Avis non trouvé"),
            new OA\Response(response: 500, description: "Erreur du serveur à l'interne")
        ]
    )]
    public function destroy($id){
        try {
            Review::findOrFail($id)->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $ex) {
            abort(404, 'Invalid id');
        } catch (Exception $ex) {
            abort(500, 'Server error');
        }
    }
}
