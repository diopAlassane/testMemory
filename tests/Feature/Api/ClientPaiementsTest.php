<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Client;
use App\Models\Paiement;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientPaiementsTest extends TestCase
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
    public function it_gets_client_paiements(): void
    {
        $client = Client::factory()->create();
        $paiements = Paiement::factory()
            ->count(2)
            ->create([
                'client_id' => $client->id,
            ]);

        $response = $this->getJson(
            route('api.clients.paiements.index', $client)
        );

        $response->assertOk()->assertSee($paiements[0]->print_pdf);
    }

    /**
     * @test
     */
    public function it_stores_the_client_paiements(): void
    {
        $client = Client::factory()->create();
        $data = Paiement::factory()
            ->make([
                'client_id' => $client->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.clients.paiements.store', $client),
            $data
        );

        $this->assertDatabaseHas('paiements', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $paiement = Paiement::latest('id')->first();

        $this->assertEquals($client->id, $paiement->client_id);
    }
}
