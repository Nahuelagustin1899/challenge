<?php

namespace App\Services;

use App\Enums\App;
use App\Helpers\ListHelper;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public static function create( Array $hotelData ) : Hotel
    {
        return Hotel::create( $hotelData );
    }

    public static function update( Array $hotelData, int $hotelId ) : Hotel
    {
        try {
            DB::beginTransaction();
            $hotel = Hotel::findOrFail($hotelId);
            $hotel->update($hotelData);
    
            DB::commit();
            return $hotel;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('An error occurred while updating the hotel.', 500, $e);
        }
    }

    public static function delete( int $hotelId ) :  Hotel | null
    {
        $hotel = Hotel::find( $hotelId );
        if( $hotel ) $hotel->delete();

        return $hotel;
    }

    public static function paginate( array $filters = [] )
	{
        $filters = ListHelper::setListFields( $filters, [
            'id',
            'name',
            'description',
            'address',
            'price_per_night',
            'min_rating',
            'max_rating',
            'min_price',
            'max_price',
        ] );

		$query = Hotel::query();

		return $query->id( $filters[ 'id' ] )
                    ->name( $filters[ 'name' ] )
                    ->description( $filters[ 'description' ] )
                    ->address( $filters[ 'address' ] )
                    ->minRating( $filters['min_rating'] )
                    ->maxRating( $filters['max_rating'] )
                    ->minPrice( $filters['min_price'] )
                    ->maxPrice( $filters['max_price'] )
                    ->orderBy( ListHelper::orderField( $filters[ App::DEFAULT_LIST_ORDER_FIELD_KEY ] ), ListHelper::orderMode( $filters[ App::DEFAULT_LIST_ORDER_MODE_KEY ] ) )
                    ->paginate( ListHelper::perPage( $filters[ App::DEFAULT_LIST_REGS_PER_PAGE_KEY ] ) );

	}
}
