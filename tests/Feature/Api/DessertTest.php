<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Dessert;

use App\Models\Menu;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DessertTest extends TestCase
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
    public function it_gets_desserts_list(): void
    {
        $desserts = Dessert::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.desserts.index'));

        $response->assertOk()->assertSee($desserts[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_dessert(): void
    {
        $data = Dessert::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.desserts.store'), $data);

        $this->assertDatabaseHas('desserts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_dessert(): void
    {
        $dessert = Dessert::factory()->create();

        $menu = Menu::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'menu_id' => $menu->id,
        ];

        $response = $this->putJson(
            route('api.desserts.update', $dessert),
            $data
        );

        $data['id'] = $dessert->id;

        $this->assertDatabaseHas('desserts', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_dessert(): void
    {
        $dessert = Dessert::factory()->create();

        $response = $this->deleteJson(route('api.desserts.destroy', $dessert));

        $this->assertModelMissing($dessert);

        $response->assertNoContent();
    }
}
