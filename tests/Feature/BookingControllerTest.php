<?php

namespace Tests\Feature;

use App\Enums\App;
use App\Mail\BookingMail;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public $filters = [
        'id' => 5,
        'customer_name' => 'John Doe',
        'customer_email' => 'john.doe@example.com',
        'tour_name' => 'Amazing Tour',
        'hotel_name' => 'Amazing Hotel',
        'list_order_mode_asc' => 'asc',
        'list_order_mode_desc' => 'desc',
        'list_regs_per_page' => 10,
    ];

    // ***************************** INDEX ***************************** //

    public function testList()
    {
        $response = $this->getJson( 'api/bookings' );
        $response->assertStatus( 200 );
    }

    public function testListWithCustomerNameFilter()
    {
        $response = $this->getJson( 'api/bookings?customer_name=' . $this->filters[ 'customer_name' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithCustomerEmailFilter()
    {
        $response = $this->getJson( 'api/bookings?customer_email=' . $this->filters[ 'customer_email' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithTourNameFilter()
    {
        $response = $this->getJson('api/bookings?tour_name=' . $this->filters['tour_name']);
        $response->assertStatus(200);
    }

    public function testListWithHotelNameFilter()
    {
        $response = $this->getJson('api/bookings?hotel_name=' . $this->filters['hotel_name']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByCustomerNameAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=customer_name&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByCustomerNameDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=customer_name&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByCustomerEmailAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=customer_email&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByCustomerEmailDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=customer_email&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByBookingDatelAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=booking_date&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByBookingDateDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=booking_date&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }


    public function testListPerPage()
    {
        $response = $this->getJson('api/bookings?list_regs_per_page=' . $this->filters['list_regs_per_page']);
        $response->assertStatus(200);
    }

    public function testListWithDateRangeFilter()
    {
        $start_date = '2024-01-01';
        $end_date = '2024-12-31';

        Booking::factory()->create(['booking_date' => '2024-03-15']);
        Booking::factory()->create(['booking_date' => '2023-07-20']);
        Booking::factory()->create(['booking_date' => '2024-10-10']);

        $response = $this->getJson("api/bookings?start_date={$start_date}&end_date={$end_date}");

        $response->assertStatus(200);

        $response->assertJsonCount(2, 'data');
    }

    // ***************************** SHOW ***************************** //

    public function testShowReturnsBookingResourceWhenBookingExists()
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $booking->id,
                'tour_id' => $booking->tour_id,
                'hotel_id' => $booking->hotel_id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'number_of_people' => $booking->number_of_people,
                'booking_date' => $booking->booking_date,
            ]
        ]);
    }

    public function testShowReturnsErrorWhenBookingDoesNotExist()
    {
        $response = $this->getJson('/api/bookings/999');

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Booking not found',
        ]);
    }

    // ***************************** DESTROY ***************************** //

    public function testDestroyReturnsBookingResourceWhenBookingExists()
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('bookings', ['id' => $booking->id]);
    }

    public function testDestroyReturnsErrorWhenBookingDoesNotExist()
    {
        $response = $this->deleteJson('/api/bookings/999');

        $response->assertStatus(404);

        $response->assertJson(['error' => 'Booking not found']);
    }

    // ***************************** STORE ***************************** //

    public function testStoreCreatesAndReturnsBooking()
    {
        Mail::fake();

        $tour = Tour::factory()->create();
        $hotel = Hotel::factory()->create();

        $bookingData = [
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'number_of_people' => 4,
            'booking_date' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'tour_id' => $bookingData['tour_id'],
                'hotel_id' => $bookingData['hotel_id'],
                'customer_name' => $bookingData['customer_name'],
                'customer_email' => $bookingData['customer_email'],
                'number_of_people' => $bookingData['number_of_people'],
                'booking_date' => $bookingData['booking_date'],
            ]
        ]);

        $this->assertDatabaseHas('bookings', $bookingData);

        Mail::assertSent(BookingMail::class, function ($mail) use ($bookingData) {
            return $mail->hasTo($bookingData['customer_email']);
        });
    }

    public function testStoreFailsWithMissingRequiredFields()
    {
        $bookingData = [];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['tour_id', 'hotel_id', 'number_of_people', 'booking_date', 'customer_name', 'customer_email']);
    }

    public function testStoreFailsWithInvalidData()
    {
        $bookingData = [
            'tour_id' => 9999,
            'hotel_id' => 9999,
            'customer_name' => str_repeat('A', 256), 
            'customer_email' => 'invalid-email',
            'number_of_people' => 0,
            'booking_date' => 'invalid-date',
        ];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'tour_id',
            'hotel_id',
            'customer_name',
            'customer_email',
            'number_of_people',
            'booking_date',
        ]);
    }

    // ***************************** UPDATE ***************************** //

    public function testUpdateUpdatesAndReturnsBooking()
    {
        $tour = Tour::factory()->create();
        $hotel = Hotel::factory()->create();
        $booking = Booking::factory()->create();

        $bookingData = [
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'customer_name' => 'Updated Name',
            'customer_email' => 'updated.email@example.com',
            'number_of_people' => 3,
            'booking_date' => now()->addDay()->toDateString(),
        ];

        $response = $this->putJson("/api/bookings/{$booking->id}", $bookingData);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'tour_id' => $bookingData['tour_id'],
                'hotel_id' => $bookingData['hotel_id'],
                'customer_name' => $bookingData['customer_name'],
                'customer_email' => $bookingData['customer_email'],
                'number_of_people' => $bookingData['number_of_people'],
                'booking_date' => $bookingData['booking_date'],
            ]
        ]);

        $this->assertDatabaseHas('bookings', $bookingData);
    }

    public function testUpdateFailsWithInvalidData()
    {
        $booking = Booking::factory()->create();
    
        $bookingData = [
            'tour_id' => 9999,
            'hotel_id' => 9999,
            'customer_name' => str_repeat('A', 256), 
            'customer_email' => 'invalid-email',
            'number_of_people' => 0,
            'booking_date' => 'invalid-date',
        ];
    
        $response = $this->putJson("/api/bookings/{$booking->id}", $bookingData);
    
        $response->assertStatus(422);
    
        $response->assertJsonValidationErrors([
            'tour_id',
            'hotel_id',
            'customer_name',
            'customer_email',
            'number_of_people',
            'booking_date',
        ]);
    }

    // ***************************** EXPORT CSV ***************************** //

    public function testExportBookingsDispatchesJob()
    {
        Bus::fake();

        $response = $this->getJson('/api/bookings/export');

        $response->assertStatus(200);

        Bus::assertDispatched(\App\Jobs\ExportBookingsJob::class);

        $response->assertJson([
            'message' => 'The export of bookings to Excel has been successfully initiated. The file will be available shortly.',
        ]);

        $response->assertJsonStructure([
            'message',
            'fileUrl',
        ]);
    }

    // ***************************** CANCEL BOOKING ***************************** //

    public function testCancelBookingSuccessfully()
    {
        $tour = Tour::factory()->create([
            'price' => '453.40',
        ]);
    
        $hotel = Hotel::factory()->create([
            'price_per_night' => '490.12',
        ]);
    
        $booking = Booking::factory()->create([
            'tour_id' => $tour->id,
            'hotel_id' => $hotel->id,
            'booking_date' => '2006-01-18',
            'status' => App::STATUS_RESERVED,
        ]);
    
        $response = $this->postJson("/api/bookings/{$booking->id}/cancel");
    
        $response->assertStatus(200);
    
        $response->assertJson([
            'data' => [
                'id' => $booking->id,
                'tour_id' => $booking->tour_id,
                'hotel_id' => $booking->hotel_id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'number_of_people' => $booking->number_of_people,
                'booking_date' => '2006-01-18',
                'status' => App::STATUS_CANCELED,
                'tour' => [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'description' => $tour->description,
                    'price' => '453.40',
                    'start_date' => $tour->start_date,
                    'end_date' => $tour->end_date,
                ],
                'hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'description' => $hotel->description,
                    'address' => $hotel->address,
                    'rating' => $hotel->rating,
                    'price_per_night' => '490.12',
                ],
            ]
        ]);
    }
    
    public function testCancelAlreadyCancelledBooking()
    {
        $booking = Booking::factory()->create(['status' => App::STATUS_CANCELED]);

        $response = $this->postJson("/api/bookings/{$booking->id}/cancel");

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'The reservation is already cancelled.',
        ]);
    }

    public function testCancelBookingWithInvalidStatus()
    {
        $booking = Booking::factory()->create(['status' => 999]);

        $response = $this->postJson("/api/bookings/{$booking->id}/cancel");

        $response->assertStatus(400);

        $response->assertJson([
            'error' => 'Reservation status not valid for cancellation.',
        ]);
    }

    public function testCancelNonExistentBooking()
    {
        $response = $this->postJson('/api/bookings/999/cancel');

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Booking not found',
        ]);
    }
    
}
