<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'rating',
        'price_per_night',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes

    public function scopeId( $query, int | string $id, string $table = 'hotels' )
	{
		if( $id )
		{
			return $query->where( $table . '.id', $id );
		}
	}

    public function scopeName( $query, string $name, string $table = 'hotels' )
    {
        if( trim( $name ) != '' )
        {
            return $query->where( $table . '.name', 'LIKE', '%' . $name . '%' );
        }
    }

    public function scopeDescription( $query, string $description, string $table = 'hotels' )
    {
        if( trim( $description ) != '' )
        {
            return $query->where( $table . '.description', 'LIKE', '%' . $description . '%' );
        }
    }

    public function scopeAddress( $query, string $address, string $table = 'hotels' )
    {
        if( trim( $address ) != '' )
        {
            return $query->where( $table . '.address', 'LIKE', '%' . $address . '%' );
        }
    }

    public function scopeMinRating($query, $minRating, string $table = 'hotels' )
    {
        if ( trim( $minRating ) != '' ) {
            return $query->where( $table . '.rating', '>=', $minRating );
        }
        return $query;
    }

    public function scopeMaxRating( $query, $maxRating, string $table = 'hotels' )
    {
        if ( trim( $maxRating ) != '' ) {
            return $query->where( $table . '.rating', '<=', $maxRating );
        }
        return $query;
    }

    public function scopeMinPrice( $query, $minPrice, string $table = 'hotels' )
    {
        if ( trim( $minPrice ) != '' ) {
            return $query->where( $table . '.price_per_night', '>=', $minPrice );
        }
        return $query;
    }

    public function scopeMaxPrice( $query, $maxPrice, string $table = 'hotels' )
    {
        if ( trim( $maxPrice ) != '' ) {
            return $query->where( $table . '.price_per_night', '<=', $maxPrice );
        }
        return $query;
    }

    protected static function booted()
    {
        static::deleting(function ($hotel) {
            if ($hotel->isForceDeleting()) {
                $hotel->bookings()->forceDelete();
            } else {
                $hotel->bookings()->delete();
            }
        });
    }
    
}
