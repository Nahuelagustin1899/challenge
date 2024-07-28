<?php

namespace App\Services;

use App\Enums\App;
use App\Helpers\ListHelper;
use App\Jobs\ExportBookingsJob;
use App\Mail\BookingMail;
use App\Models\Booking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    public static function create(Array $bookingData) : Booking
    {
        $bookingData['status'] = App::STATUS_RESERVED
        ;
        DB::beginTransaction();
    
        try {
            $booking = Booking::create($bookingData);
            Mail::to($booking->customer_email)->send(new BookingMail($booking));
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    
        return $booking;
    }
    

    public static function update(array $bookingData, int $bookingId) : Booking
    {
        try {
            DB::beginTransaction();
            $booking = Booking::findOrFail($bookingId);
            $booking->update($bookingData);
    
            DB::commit();
            return $booking;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('An error occurred while updating the booking.', 500, $e);
        }
    }
    

    public static function delete( int $bookingId ) :  Booking | null
    {
        $booking = Booking::find( $bookingId );
        if( $booking ) $booking->delete();

        return $booking;
    }

    public static function paginate( array $filters = [] )
	{
        $filters = ListHelper::setListFields( $filters, [
            'id',
            'customer_name',
            'customer_email',
            'tour_name',
            'hotel_name',
            'start_date',
            'end_date',
        ] );

        $query = Booking::query();

		return $query->id( $filters[ 'id' ] )
                    ->customerName( $filters[ 'customer_name' ] )
                    ->customerEmail( $filters[ 'customer_email' ] )
                    ->tourName( $filters[ 'tour_name' ] )
                    ->hotelName( $filters[ 'hotel_name' ] )
                    ->dateRange($filters['start_date'], $filters['end_date'])
                    ->orderBy( ListHelper::orderField( $filters[ App::DEFAULT_LIST_ORDER_FIELD_KEY ] ), ListHelper::orderMode( $filters[ App::DEFAULT_LIST_ORDER_MODE_KEY ] ) )
                    ->paginate( ListHelper::perPage( $filters[ App::DEFAULT_LIST_REGS_PER_PAGE_KEY ] ) );

	}

    public static function exportBookings()
    {
        $fileName = 'bookings_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        Bus::dispatch(new ExportBookingsJob($fileName));

        $fileUrl = url('/storage/' . $fileName);

        return response()->json([
            'message' => 'The export of bookings to Excel has been successfully initiated. The file will be available shortly.',
            'fileUrl' => $fileUrl,
        ]);
    }

    public static function cancel($bookingId)
    {
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return false;
        }
    
        if ($booking->status === App::STATUS_CANCELED) {
            throw new \InvalidArgumentException('The reservation is already cancelled.');
        } elseif ($booking->status !== App::STATUS_RESERVED) {
            throw new \InvalidArgumentException('Reservation status not valid for cancellation.');
        }
    
        $booking->status = App::STATUS_CANCELED;
        $booking->save();
    
        return $booking;
    }
    
}
