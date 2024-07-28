<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'start_date',
        'end_date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeId( $query, int | string $id, string $table = 'tours' )
	{
		if( $id )
		{
			return $query->where( $table . '.id', $id );
		}
	}

    public function scopeName( $query, string $name, string $table = 'tours' )
    {
        if( trim( $name ) != '' )
        {
            return $query->where( $table . '.name', 'LIKE', '%' . $name . '%' );
        }
    }

    public function scopeDescription( $query, string $description, string $table = 'tours' )
    {
        if( trim( $description ) != '' )
        {
            return $query->where( $table . '.description', 'LIKE', '%' . $description . '%' );
        }
    }

    public function scopeStartDateFrom($query, $starDateFrom, string $table = 'tours' )
    {
        if ( trim( $starDateFrom ) != '' ) {
            return $query->where( $table . '.start_date', '>=', $starDateFrom );
        }
        return $query;
    }

    public function scopeEndDateUntil( $query, $endDateUntil, string $table = 'tours' )
    {
        if ( trim( $endDateUntil ) != '' ) {
            return $query->where( $table . '.end_date', '<=', $endDateUntil );
        }
        return $query;
    }

    public function scopeMinPrice( $query, $minPrice, string $table = 'tours' )
    {
        if ( trim( $minPrice ) != '' ) {
            return $query->where( $table . '.price', '>=', $minPrice );
        }
        return $query;
    }

    public function scopeMaxPrice( $query, $maxPrice, string $table = 'tours' )
    {
        if ( trim( $maxPrice ) != '' ) {
            return $query->where( $table . '.price', '<=', $maxPrice );
        }
        return $query;
    }

    protected static function booted()
    {
        static::deleting(function ($tour) {
            if ($tour->isForceDeleting()) {
                $tour->bookings()->forceDelete();
            } else {
                $tour->bookings()->delete();
            }
        });
    }


}
