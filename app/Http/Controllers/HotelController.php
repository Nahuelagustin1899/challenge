<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hotel\ListRequest;
use App\Http\Requests\Hotel\StoreRequest;
use App\Http\Requests\Hotel\UpdateRequest;
use App\Http\Resources\Hotel\HotelCollection;
use App\Http\Resources\Hotel\HotelResource;
use App\Models\Hotel;
use App\Services\HotelService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HotelController extends Controller
{
    public function index( ListRequest $request ) : HotelCollection
    {
        $hotels = HotelService::paginate( $request->validated() );
        return new HotelCollection( $hotels );
    }

    public function store(StoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $hotel = HotelService::create( $request->validated() );
        return response()->json( [
            'data' => HotelResource::make( $hotel ),
        ], 201 );
    }

    public function show( $hotelId ) : \Illuminate\Http\JsonResponse
    {
        $hotel = Hotel::find( $hotelId );
        return $hotel ?

        response()->json( [
            'data' => HotelResource::make( $hotel ),
        ] ):

        response()->json( [
            'error' => 'Hotel not found',
        ], 404 );
    }

    public function update( UpdateRequest $request, $hotelId ) : \Illuminate\Http\JsonResponse
    {
        try {
            $hotel = HotelService::update($request->validated(), $hotelId);
            return response()->json([
                'data' => HotelResource::make($hotel),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Hotel not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the hotel.',
            ], 500);
        }
    }

    public function destroy( $hotelId ) : \Illuminate\Http\JsonResponse
    {
        $hotel = HotelService::delete( $hotelId );
        return $hotel ?

        response()->json( null, 204 ):

        response()->json( [
            'error' => 'Hotel not found',
        ], 404 );
    }
}
