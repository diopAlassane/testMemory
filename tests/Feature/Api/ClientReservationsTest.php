<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Client;
use App\Models\Reservation;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientReservationsTest extends TestCase
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
    public function it_gets_client_reservations(): void
    {
        $client = Client::factory()->create();
        $reservations = Reservation::factory()
            ->count(2)
            ->create([
                'client_id' => $client->id,
            ]);

        $response = $this->getJson(
            route('api.clients.reservations.index', $client)
        );

        $response->assertOk()->assertSee($reservations[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_client_reservations(): void
    {
        $client = Client::factory()->create();
        $data = Reservation::factory()
            ->make([
                'client_id' => $client->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.clients.reservations.store', $client),
            $data
        );

        $this->assertDatabaseHas('reservations', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $reservation = Reservation::latest('id')->first();

        $this->assertEquals($client->id, $reservation->client_id);
    }
}
