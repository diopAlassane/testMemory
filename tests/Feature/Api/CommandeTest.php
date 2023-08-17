<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Commande;

use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandeTest extends TestCase
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
    public function it_gets_commandes_list(): void
    {
        $commandes = Commande::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.commandes.index'));

        $response->assertOk()->assertSee($commandes[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_commande(): void
    {
        $data = Commande::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.commandes.store'), $data);

        $this->assertDatabaseHas('commandes', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.commandes.update', $commande),
            $data
        );

        $data['id'] = $commande->id;

        $this->assertDatabaseHas('commandes', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_commande(): void
    {
        $commande = Commande::factory()->create();

        $response = $this->deleteJson(
            route('api.commandes.destroy', $commande)
        );

        $this->assertModelMissing($commande);

        $response->assertNoContent();
    }
}
