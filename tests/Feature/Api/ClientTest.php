<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
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
    public function it_gets_clients_list(): void
    {
        $clients = Client::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.clients.index'));

        $response->assertOk()->assertSee($clients[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_client(): void
    {
        $data = Client::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.clients.store'), $data);

        $this->assertDatabaseHas('clients', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_client(): void
    {
        $client = Client::factory()->create();

        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
        ];

        $response = $this->putJson(route('api.clients.update', $client), $data);

        $data['id'] = $client->id;

        $this->assertDatabaseHas('clients', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson(route('api.clients.destroy', $client));

        $this->assertModelMissing($client);

        $response->assertNoContent();
    }
}
