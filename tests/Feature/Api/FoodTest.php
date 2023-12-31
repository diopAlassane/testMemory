<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Food;

use App\Models\Menu;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodTest extends TestCase
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
    public function it_gets_all_food_list(): void
    {
        $allFood = Food::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.all-food.index'));

        $response->assertOk()->assertSee($allFood[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_food(): void
    {
        $data = Food::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.all-food.store'), $data);

        $this->assertDatabaseHas('food', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_food(): void
    {
        $food = Food::factory()->create();

        $menu = Menu::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'menu_id' => $menu->id,
        ];

        $response = $this->putJson(route('api.all-food.update', $food), $data);

        $data['id'] = $food->id;

        $this->assertDatabaseHas('food', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_food(): void
    {
        $food = Food::factory()->create();

        $response = $this->deleteJson(route('api.all-food.destroy', $food));

        $this->assertModelMissing($food);

        $response->assertNoContent();
    }
}
