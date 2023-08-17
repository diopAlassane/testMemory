<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Paiement;

use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaiementTest extends TestCase
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
    public function it_gets_paiements_list(): void
    {
        $paiements = Paiement::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.paiements.index'));

        $response->assertOk()->assertSee($paiements[0]->print_pdf);
    }

    /**
     * @test
     */
    public function it_stores_the_paiement(): void
    {
        $data = Paiement::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.paiements.store'), $data);

        $this->assertDatabaseHas('paiements', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $client = Client::factory()->create();

        $data = [
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'print_pdf' => $this->faker->text(255),
            'client_id' => $client->id,
        ];

        $response = $this->putJson(
            route('api.paiements.update', $paiement),
            $data
        );

        $data['id'] = $paiement->id;

        $this->assertDatabaseHas('paiements', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $response = $this->deleteJson(
            route('api.paiements.destroy', $paiement)
        );

        $this->assertModelMissing($paiement);

        $response->assertNoContent();
    }
}
