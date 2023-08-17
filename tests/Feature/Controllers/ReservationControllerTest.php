<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Reservation;

use App\Models\Client;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_reservations(): void
    {
        $reservations = Reservation::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('reservations.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.reservations.index')
            ->assertViewHas('reservations');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_reservation(): void
    {
        $response = $this->get(route('reservations.create'));

        $response->assertOk()->assertViewIs('app.reservations.create');
    }

    /**
     * @test
     */
    public function it_stores_the_reservation(): void
    {
        $data = Reservation::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('reservations.store'), $data);

        $this->assertDatabaseHas('reservations', $data);

        $reservation = Reservation::latest('id')->first();

        $response->assertRedirect(route('reservations.edit', $reservation));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->get(route('reservations.show', $reservation));

        $response
            ->assertOk()
            ->assertViewIs('app.reservations.show')
            ->assertViewHas('reservation');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->get(route('reservations.edit', $reservation));

        $response
            ->assertOk()
            ->assertViewIs('app.reservations.edit')
            ->assertViewHas('reservation');
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

        $response = $this->put(
            route('reservations.update', $reservation),
            $data
        );

        $data['id'] = $reservation->id;

        $this->assertDatabaseHas('reservations', $data);

        $response->assertRedirect(route('reservations.edit', $reservation));
    }

    /**
     * @test
     */
    public function it_deletes_the_reservation(): void
    {
        $reservation = Reservation::factory()->create();

        $response = $this->delete(route('reservations.destroy', $reservation));

        $response->assertRedirect(route('reservations.index'));

        $this->assertModelMissing($reservation);
    }
}
