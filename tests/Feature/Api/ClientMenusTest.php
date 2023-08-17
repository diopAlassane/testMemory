<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Menu;
use App\Models\Client;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientMenusTest extends TestCase
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
    public function it_gets_client_menus(): void
    {
        $client = Client::factory()->create();
        $menus = Menu::factory()
            ->count(2)
            ->create([
                'client_id' => $client->id,
            ]);

        $response = $this->getJson(route('api.clients.menus.index', $client));

        $response->assertOk()->assertSee($menus[0]->drink_list);
    }

    /**
     * @test
     */
    public function it_stores_the_client_menus(): void
    {
        $client = Client::factory()->create();
        $data = Menu::factory()
            ->make([
                'client_id' => $client->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.clients.menus.store', $client),
            $data
        );

        $this->assertDatabaseHas('menus', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $menu = Menu::latest('id')->first();

        $this->assertEquals($client->id, $menu->client_id);
    }
}
