<?php

namespace Tests\Feature;

use App\Models\Hotel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelControllerTest extends TestCase
{
    use RefreshDatabase;

    public $filters = [
        'id' => 1,
        'name' => 'Name test',
        'description' => 'Description test',
        'address' => 'Address test',
        'rating' => 5,
        'price_per_night' => 300,
        'min_rating' => 3,
        'max_rating' => 4,
        'min_price' => 300,
        'max_price' => 400,
        'list_order_mode_asc' => 'asc',
        'list_order_mode_desc' => 'desc',
        'list_regs_per_page' => 10,
    ];

    // ***************************** INDEX ***************************** //

    public function testList()
    {
        $response = $this->getJson( 'api/hotels' );
        $response->assertStatus( 200 );
    }

    public function testListWithNameFilter()
    {
        $response = $this->getJson( 'api/hotels?name=' . $this->filters[ 'name' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithDescriptionFilter()
    {
        $response = $this->getJson( 'api/hotels?description=' . $this->filters[ 'description' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithAddressFilter()
    {
        $response = $this->getJson( 'api/hotels?address=' . $this->filters[ 'address' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithRatingFilter()
    {
        $response = $this->getJson( 'api/hotels?rating=' . $this->filters[ 'rating' ] );
        $response->assertStatus( 200 );
    }
    

    public function testListWithMinRatingFilter()
    {
        $response = $this->getJson( 'api/hotels?min_rating=' . $this->filters[ 'min_rating' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithMaxRatingFilter()
    {
        $response = $this->getJson( 'api/hotels?max_rating=' . $this->filters[ 'max_rating' ] );
        $response->assertStatus( 200 );
    }


    public function testListWithMinPriceFilter()
    {
        $response = $this->getJson( 'api/hotels?min_price=' . $this->filters[ 'min_price' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithMaxPriceFilter()
    {
        $response = $this->getJson( 'api/hotels?max_price=' . $this->filters[ 'max_price' ] );
        $response->assertStatus( 200 );
    }

    public function testListHotelsOrderedByNameAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=name&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByNameDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=name&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByDescriptionAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=description&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByDescriptionDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=description&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByAddressAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=address&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByAddressDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=address&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByRatingAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=rating&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByRatingDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=rating&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByPricePerNightAsc()
    {
        $response = $this->getJson('api/hotels?list_order_field=price_per_night&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListHotelsOrderedByPricePerNightDesc()
    {
        $response = $this->getJson('api/hotels?list_order_field=price_per_night&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListPerPage()
    {
        $response = $this->getJson('api/hotels?list_regs_per_page=' . $this->filters['list_regs_per_page']);
        $response->assertStatus(200);
    }


    // ***************************** SHOW ***************************** //

    public function testShowReturnsHotelResourceWhenHotelExists()
    {
        $hotel = Hotel::factory()->create();

        $response = $this->getJson("/api/hotels/{$hotel->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'description' => $hotel->description,
                'address' => $hotel->address,
                'rating' => $hotel->rating,
                'price_per_night' => $hotel->price_per_night,
            ]
        ]);
    }

    public function testShowReturnsErrorWhenHotelDoesNotExist()
    {
        $response = $this->getJson('/api/hotels/999');

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Hotel not found',
        ]);
    }

    // ***************************** DESTROY ***************************** //

    public function testDestroyReturnsHotelResourceWhenHotelExists()
    {
        $hotel = Hotel::factory()->create();

        $response = $this->deleteJson("/api/hotels/{$hotel->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('hotels', ['id' => $hotel->id]);
    }

    public function testDestroyReturnsErrorWhenHotelDoesNotExist()
    {
        $response = $this->deleteJson('/api/hotels/999');

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Hotel not found',
        ]);
    }

    // ***************************** STORE ***************************** //

    public function testStoreCreatesAndReturnsHotel()
    {
        $hotelData = [
            'name' => 'Test Hotel',
            'description' => 'A test hotel description',
            'address' => '123 Test Street',
            'rating' => 5,
            'price_per_night' => 100,
        ];

        $response = $this->postJson('/api/hotels', $hotelData);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'name' => 'Test Hotel',
                'description' => 'A test hotel description',
                'address' => '123 Test Street',
                'rating' => 5,
                'price_per_night' => 100,
            ]
        ]);

        $this->assertDatabaseHas('hotels', $hotelData);
    }

    public function testStoreFailsWithMissingRequiredFields()
    {
        $hotelData = [];

        $response = $this->postJson('/api/hotels', $hotelData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'address', 'description', 'rating', 'price_per_night']);
    }

    public function testStoreFailsWithInvalidData()
    {
        $hotelData = [
            'name' => str_repeat('A', 256),
            'description' => '',
            'address' => '123 Test Street',
            'rating' => 6,
            'price_per_night' => 'invalid',
        ];

        $response = $this->postJson('/api/hotels', $hotelData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'description',
            'rating',
            'price_per_night',
        ]);
    }

    // ***************************** STORE ***************************** //

    public function testUpdateUpdatesAndReturnsHotel()
    {
        $hotel = Hotel::factory()->create();

        $hotelData = [
            'name' => 'Updated Hotel',
            'description' => 'An updated hotel description',
            'address' => '456 Updated Street',
            'rating' => 4,
            'price_per_night' => 150,
        ];

        $response = $this->putJson("/api/hotels/{$hotel->id}", $hotelData);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'Updated Hotel',
                'description' => 'An updated hotel description',
                'address' => '456 Updated Street',
                'rating' => 4,
                'price_per_night' => 150,
            ]
        ]);

        $this->assertDatabaseHas('hotels', $hotelData);
    }

    public function testUpdateFailsWithInvalidData()
    {
        $hotel = Hotel::factory()->create();

        $hotelData = [
            'name' => str_repeat('A', 256),
            'rating' => 6,
            'price_per_night' => 'invalid',
        ];

        $response = $this->putJson("/api/hotels/{$hotel->id}", $hotelData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'rating',
            'price_per_night',
        ]);
    }
}
