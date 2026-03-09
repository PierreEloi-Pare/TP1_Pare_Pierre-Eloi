<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewController extends Controller
{
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
