<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'hotel_id',
        'customer_name',
        'customer_email',
        'number_of_people',
        'booking_date',
        'status'
    ];


    // Relations
    public function tour(): BelongsTo
    {
        return $this->belongsTo( Tour::class, 'tour_id', 'id' );
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo( Hotel::class, 'hotel_id', 'id' );
    }

    // Scopes
    public function scopeId( $query, int | string $id, string $table = 'bookings' )
	{
		if( $id )
		{
			return $query->where( $table . '.id', $id );
		}
	}

    public function scopeCustomerName( $query, string $customerName, string $table = 'bookings' )
	{
		if( trim( $customerName ) != '' )
		{
			return $query->where( $table . '.customer_name', 'LIKE', '%' . $customerName . '%' );
		}
	}

    public function scopeCustomerEmail( $query, string $customerEmail, string $table = 'bookings' )
	{
		if( trim( $customerEmail ) != '' )
		{
			return $query->where( $table . '.customer_email', 'LIKE', '%' . $customerEmail . '%' );
		}
	}

    public function scopeTourName( $query, string $tourName )
	{
		if( trim( $tourName ) )
		{
            return $query->whereHas( 'tour', function( $q ) use ( $tourName )
            {
                $q->where( 'name', 'LIKE', '%' . $tourName . '%' );
            });
		}
	}

    public function scopeHotelName( $query, string $hotelName )
	{
		if( trim( $hotelName ) )
		{
            return $query->whereHas( 'hotel', function( $q ) use ( $hotelName )
            {
                $q->where( 'name', 'LIKE', '%' . $hotelName . '%' );
            });
		}
	}

    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        if( trim( $startDate )  && trim( $endDate ))
		{
            return $query->whereBetween('booking_date', [$startDate, $endDate]);
        }
    }
}
