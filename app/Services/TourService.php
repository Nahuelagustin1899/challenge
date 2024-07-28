<?php

namespace App\Services;

use App\Enums\App;
use App\Helpers\ListHelper;
use App\Models\Tour;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TourService
{
    public static function create( Array $tourData ) : Tour
    {
        return Tour::create( $tourData );
    }

    public static function update( Array $tourData, int $tourId ) : Tour
    {

        try {
            DB::beginTransaction();
            $booking = Tour::findOrFail($tourId);
            $booking->update($tourData);
    
            DB::commit();
            return $booking;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('An error occurred while updating the tour.', 500, $e);
        }
    }

    public static function delete( int $tourId ) :  Tour | null
    {
        $tour = Tour::find( $tourId );
        if( $tour ) $tour->delete();

        return $tour;
    }

    public static function paginate( array $filters = [] )
	{
        $filters = ListHelper::setListFields( $filters, [
            'id',
            'name',
            'description',
            'start_date',
            'end_date',
            'start_date_from',
            'end_date_until',
            'price',
            'min_price',
            'max_price'
        ] );

		$query = Tour::query();

		return $query->id( $filters[ 'id' ] )
                    ->name( $filters[ 'name' ] )
                    ->description( $filters[ 'description' ] )
                    ->startDateFrom( $filters['start_date_from'] )
                    ->endDateUntil( $filters['end_date_until'] )
                    ->minPrice( $filters['min_price'] )
                    ->maxPrice( $filters['max_price'] )
                    ->orderBy( ListHelper::orderField( $filters[ App::DEFAULT_LIST_ORDER_FIELD_KEY ] ), ListHelper::orderMode( $filters[ App::DEFAULT_LIST_ORDER_MODE_KEY ] ) )
                    ->paginate( ListHelper::perPage( $filters[ App::DEFAULT_LIST_REGS_PER_PAGE_KEY ] ) );

	}
}
