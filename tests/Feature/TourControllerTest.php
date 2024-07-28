<?php

namespace Tests\Feature;

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourControllerTest extends TestCase
{
    use RefreshDatabase;

    public $filters = [
        'id' => 1,
        'name' => 'Name test',
        'description' => 'Description test',
        'start_date_from' => '2018-12-29',
        'end_date_until' => '2024-12-29',
        'min_price' => 300,
        'max_price' => 400,
        'list_order_mode_asc' => 'asc',
        'list_order_mode_desc' => 'desc',
        'list_regs_per_page' => 10,
    ];

    // ***************************** INDEX ***************************** //

    public function testList()
    {
        $response = $this->getJson( 'api/tours' );
        $response->assertStatus( 200 );
    }

    public function testListWithNameFilter()
    {
        $response = $this->getJson( 'api/tours?name=' . $this->filters[ 'name' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithDescriptionFilter()
    {
        $response = $this->getJson( 'api/tours?description=' . $this->filters[ 'description' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithStartDateFromFilter()
    {
        $response = $this->getJson( 'api/tours?start_date_from=' . $this->filters[ 'start_date_from' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithEndDateFromFilter()
    {
        $response = $this->getJson( 'api/tours?end_date_until=' . $this->filters[ 'end_date_until' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithMinPriceFilter()
    {
        $response = $this->getJson( 'api/tours?min_price=' . $this->filters[ 'min_price' ] );
        $response->assertStatus( 200 );
    }

    public function testListWithMaxPriceFilter()
    {
        $response = $this->getJson( 'api/tours?max_price=' . $this->filters[ 'max_price' ] );
        $response->assertStatus( 200 );
    }

    public function testListToursOrderedByNameAsc()
    {
        $response = $this->getJson('api/tours?list_order_field=name&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByNameDesc()
    {
        $response = $this->getJson('api/tours?list_order_field=name&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByDescriptionAsc()
    {
        $response = $this->getJson('api/tours?list_order_field=description&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByDescriptionDesc()
    {
        $response = $this->getJson('api/tours?list_order_field=description&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByPriceAsc()
    {
        $response = $this->getJson('api/tours?list_order_field=price&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByPriceDesc()
    {
        $response = $this->getJson('api/tours?list_order_field=price&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByStartDateAsc()
    {
        $response = $this->getJson('api/tours?list_order_field=start_date&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByStartDateDesc()
    {
        $response = $this->getJson('api/tours?list_order_field=start_date&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByEndDateAsc()
    {
        $response = $this->getJson('api/tours?list_order_field=end_date&list_order_mode=' . $this->filters['list_order_mode_asc']);
        $response->assertStatus(200);
    }

    public function testListToursOrderedByEndDateDesc()
    {
        $response = $this->getJson('api/tours?list_order_field=end_date&list_order_mode=' . $this->filters['list_order_mode_desc']);
        $response->assertStatus(200);
    }

    // ***************************** SHOW ***************************** //

    public function testShowReturnsTourResourceWhenTourExists()
    {
        $tour = Tour::factory()->create();

        $response = $this->getJson("/api/tours/{$tour->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id' => $tour->id,
                'name' => $tour->name,
                'description' => $tour->description,
                'price' => $tour->price,
                'start_date' => $tour->start_date,
                'end_date' => $tour->end_date,
            ]
        ]);
    }

    public function testShowReturnsErrorWhenTourDoesNotExist()
    {
        $response = $this->getJson('/api/tours/999');

        $response->assertStatus(404);

        $response->assertJson([
            'error' => 'Tour not found',
        ]);
    }

    // ***************************** DESTROY ***************************** //

    public function testDestroyReturnsTourResourceWhenTourExists()
    {
        $tour = Tour::factory()->create();

        $response = $this->deleteJson("/api/tours/{$tour->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('tours', ['id' => $tour->id]);
    }

    public function testDestroyReturnsErrorWhenTourDoesNotExist()
    {
        $response = $this->deleteJson('/api/tours/999');

        $response->assertStatus(404);

        $response->assertJson(['error' => 'Tour not found']);
    }

    // ***************************** STORE ***************************** //

    public function testStoreCreatesAndReturnsTour()
    {
        $tourData = [
            'name' => 'Test Tour',
            'description' => 'A test tour description',
            'price' => 100,
            'start_date' => '2023-07-01',
            'end_date' => '2023-07-10',
        ];

        $response = $this->postJson('/api/tours', $tourData);

        $response->assertStatus(201);

        $response->assertJson([
            'data' => [
                'name' => 'Test Tour',
                'description' => 'A test tour description',
                'price' => 100,
                'start_date' => '2023-07-01',
                'end_date' => '2023-07-10',
            ]
        ]);

        $this->assertDatabaseHas('tours', $tourData);
    }

    public function testStoreFailsWithMissingRequiredFields()
    {
        $tourData = [];

        $response = $this->postJson('/api/tours', $tourData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'description', 'price', 'start_date', 'end_date']);
    }

    public function testStoreFailsWithInvalidData()
    {
        $tourData = [
            'name' => str_repeat('A', 256), 
            'description' => '',
            'price' => 'invalid',
            'start_date' => 'invalid_date',
            'end_date' => 'invalid_date',
        ];

        $response = $this->postJson('/api/tours', $tourData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'description',
            'price',
            'start_date',
            'end_date',
        ]);
    }

    public function testStoreTourFailsWithEndDateBeforeStartDate()
    {
        $tourData = [
            'start_date' => '2025-08-02',
            'end_date' => '2024-08-02',
        ];

        $response = $this->postJson('/api/tours', $tourData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'end_date',
        ]);
    }

    // ***************************** UPDATE ***************************** //


    public function testUpdateUpdatesAndReturnsTour()
    {
        $tour = Tour::factory()->create();

        $tourData = [
            'name' => 'Updated Tour',
            'description' => 'An updated tour description',
            'price' => 150,
            'start_date' => '2023-08-01',
            'end_date' => '2023-08-10',
        ];

        $response = $this->putJson("/api/tours/{$tour->id}", $tourData);

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'name' => 'Updated Tour',
                'description' => 'An updated tour description',
                'price' => 150,
                'start_date' => '2023-08-01',
                'end_date' => '2023-08-10',
            ]
        ]);

        $this->assertDatabaseHas('tours', $tourData);
    }

    public function testUpdateFailsWithInvalidData()
    {
        $tour = Tour::factory()->create();

        $tourData = [
            'name' => str_repeat('A', 256),
            'price' => 'invalid', 
            'start_date' => 'invalid_date',
            'end_date' => 'invalid_date',
        ];

        $response = $this->putJson("/api/tours/{$tour->id}", $tourData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'name',
            'price',
            'start_date',
            'end_date',
        ]);
    }

    public function testUpdateTourFailsWithEndDateBeforeStartDate()
    {
        $tourData = [
            'start_date' => '2025-08-02',
            'end_date' => '2024-08-02',
        ];

        $response = $this->postJson('/api/tours', $tourData);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'end_date',
        ]);
    }
}
