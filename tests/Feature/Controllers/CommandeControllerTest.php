<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Commande;

use App\Models\Client;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandeControllerTest extends TestCase
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
    public function it_displays_index_view_with_commandes(): void
    {
        $commandes = Commande::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('commandes.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.commandes.index')
            ->assertViewHas('commandes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_commande(): void
    {
        $response = $this->get(route('commandes.create'));

        $response->assertOk()->assertViewIs('app.commandes.create');
    }

    /**
     * @test
     */
    public function it_stores_the_commande(): void
    {
        $data = Commande::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('commandes.store'), $data);

        $this->assertDatabaseHas('commandes', $data);

        $commande = Commande::latest('id')->first();

        $response->assertRedirect(route('commandes.edit', $commande));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_commande(): void
    {
        $commande = Commande::factory()->create();

        $response = $this->get(route('commandes.show', $commande));

        $response
            ->assertOk()
            ->assertViewIs('app.commandes.show')
            ->assertViewHas('commande');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_commande(): void
    {
        $commande = Commande::factory()->create();

        $response = $this->get(route('commandes.edit', $commande));

        $response
            ->assertOk()
            ->assertViewIs('app.commandes.edit')
            ->assertViewHas('commande');
    }

    /**
     * @test
     */
    public function it_updates_the_commande(): void
    {
        $commande = Commande::factory()->create();

        $client = Client::factory()->create();

        $data = [
            'date' => $this->faker->date(),
            'time' => $this->faker->dateTime(),
            'drink' => $this->faker->text(255),
            'dessert' => $this->faker->text(255),
            'food' => $this->faker->text(255),
            'client_id' => $client->id,
        ];

        $response = $this->put(route('commandes.update', $commande), $data);

        $data['id'] = $commande->id;

        $this->assertDatabaseHas('commandes', $data);

        $response->assertRedirect(route('commandes.edit', $commande));
    }

    /**
     * @test
     */
    public function it_deletes_the_commande(): void
    {
        $commande = Commande::factory()->create();

        $response = $this->delete(route('commandes.destroy', $commande));

        $response->assertRedirect(route('commandes.index'));

        $this->assertModelMissing($commande);
    }
}
