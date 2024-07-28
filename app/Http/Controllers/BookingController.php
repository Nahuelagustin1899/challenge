<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\ListRequest;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\UpdateRequest;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingController extends Controller
{
    public function index( ListRequest $request ) : BookingCollection
    {
        $bookings = BookingService::paginate( $request->validated() );
        return new BookingCollection( $bookings );
    
        return response()->json( $bookings, 200 );
    }

    public function store( StoreRequest $request ): \Illuminate\Http\JsonResponse
    {
        try {
            $booking = BookingService::create($request->validated());
            return response()->json([
                'data' => BookingResource::make($booking),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show( $bookingId ) : \Illuminate\Http\JsonResponse
    {
        $booking = Booking::find( $bookingId );
        return $booking ?

        response()->json( [
            'data' => BookingResource::make( $booking ),
        ] ):

        response()->json( [
            'error' => 'Booking not found',
        ], 404 );
    }

    public function update(UpdateRequest $request, $bookingId) : \Illuminate\Http\JsonResponse
    {
        try {
            $booking = BookingService::update($request->validated(), $bookingId);
            return response()->json([
                'data' => BookingResource::make($booking),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Booking not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating the booking.',
            ], 500);
        }
    }

    public function destroy( $bookingId ) : \Illuminate\Http\JsonResponse
    {
        $booking = BookingService::delete( $bookingId );
        return $booking ?

        response()->json( null, 204 ):

        response()->json( [
            'error' => 'Booking not found',
        ], 404 );
    }

    public function export() 
    {
        return BookingService::exportBookings();
    }

    public function cancel($bookingId)
    {
        try {
            $booking = BookingService::cancel($bookingId);
    
            if ($booking) {
                return response()->json([
                    'data' => BookingResource::make($booking),
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Booking not found',
                ], 404);
            }
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
