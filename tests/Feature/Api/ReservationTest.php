<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Reservation;

use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_reservations_list(): void
    {
        $reservations = Reservation::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.reservations.index'));

        $response->assertOk()->assertSee($reservations[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_reservation(): void
    {
        $data = Reservation::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.reservations.store'), $data);

        $this->assertDatabaseHas('reservations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $client = Client::factory()->create();

        $data = [
            'number_table' => $this->faker->randomNumber(2),
            'date' => $this->faker->date(),
            'time' => $this->faker->dateTime(),
            'number_place' => $this->faker->text(255),
            'client_id' => $client->id,
        ];

        $response = $this->putJson(
            route('api.reservations.update', $reservation),
            $data
        );

        $data['id'] = $reservation->id;

        $this->assertDatabaseHas('reservations', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->deleteJson(
            route('api.reservations.destroy', $reservation)
        );

        $this->assertModelMissing($reservation);

        $response->assertNoContent();
    }
}
