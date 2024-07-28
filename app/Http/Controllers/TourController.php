<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tour\ListRequest;
use App\Http\Requests\Tour\StoreRequest;
use App\Http\Requests\Tour\UpdateRequest;
use App\Http\Resources\Tour\TourCollection;
use App\Http\Resources\Tour\TourResource;
use App\Models\Tour;
use App\Services\TourService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TourController extends Controller
{
    public function index( ListRequest $request ) : TourCollection
    {
        $tours = TourService::paginate( $request->validated() );
        return new TourCollection( $tours );
    
        return response()->json( $tours, 200 );
    }

    public function store(StoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $tour = TourService::create( $request->validated() );
        return response()->json( [
            'data' => TourResource::make( $tour ),
        ], 201 );
    }

    public function show( $tourId ) : \Illuminate\Http\JsonResponse
    {
        $tour = Tour::find( $tourId );
        return $tour ?

        response()->json( [
            'data' => TourResource::make( $tour ),
        ] ):

        response()->json( [
            'error' => 'Tour not found',
        ], 404 );
    }

    public function update( UpdateRequest $request, $tourId ) : \Illuminate\Http\JsonResponse
    {

        try {
            $tour = TourService::update($request->validated(), $tourId);
            return response()->json([
                'data' => TourResource::make($tour),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Tour not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the tour.',
            ], 500);
        }
        
    }
    
    public function destroy( $tourId ) : \Illuminate\Http\JsonResponse
    {
        $tour = TourService::delete( $tourId );
        return $tour ?

        response()->json( null, 204 ):

        response()->json( [
            'error' => 'Tour not found',
        ], 404 );
    }
    
}
