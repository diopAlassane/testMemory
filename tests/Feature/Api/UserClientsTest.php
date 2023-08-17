<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserClientsTest extends TestCase
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
    public function it_gets_user_clients(): void
    {
        $user = User::factory()->create();
        $clients = Client::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.clients.index', $user));

        $response->assertOk()->assertSee($clients[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_user_clients(): void
    {
        $user = User::factory()->create();
        $data = Client::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.clients.store', $user),
            $data
        );

        $this->assertDatabaseHas('clients', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $client = Client::latest('id')->first();

        $this->assertEquals($user->id, $client->user_id);
    }
}
