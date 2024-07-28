<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request )
    {
        return [
            'id' => $this->resource->id,
            'tour_id' => $this->resource->tour_id,
            'hotel_id' => $this->resource->hotel_id,
            'customer_name' => $this->resource->customer_name,
            'status' => $this->resource->status,
            'customer_email' => $this->resource->customer_email,
            'number_of_people' => $this->resource->number_of_people,
            'number_of_people' => $this->resource->number_of_people,
            'booking_date' => $this->resource->booking_date,
            'tour' => $this->resource->tour,
            'hotel' => $this->resource->hotel,
        ];
    }
}