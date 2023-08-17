<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Client;
use App\Models\Commande;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientCommandesTest extends TestCase
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
    public function it_gets_client_commandes(): void
    {
        $client = Client::factory()->create();
        $commandes = Commande::factory()
            ->count(2)
            ->create([
                'client_id' => $client->id,
            ]);

        $response = $this->getJson(
            route('api.clients.commandes.index', $client)
        );

        $response->assertOk()->assertSee($commandes[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_client_commandes(): void
    {
        $client = Client::factory()->create();
        $data = Commande::factory()
            ->make([
                'client_id' => $client->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.clients.commandes.store', $client),
            $data
        );

        $this->assertDatabaseHas('commandes', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $commande = Commande::latest('id')->first();

        $this->assertEquals($client->id, $commande->client_id);
    }
}
