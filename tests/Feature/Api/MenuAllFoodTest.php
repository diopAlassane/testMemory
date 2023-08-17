<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Menu;
use App\Models\Food;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuAllFoodTest extends TestCase
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
    public function it_gets_menu_all_food(): void
    {
        $menu = Menu::factory()->create();
        $allFood = Food::factory()
            ->count(2)
            ->create([
                'menu_id' => $menu->id,
            ]);

        $response = $this->getJson(route('api.menus.all-food.index', $menu));

        $response->assertOk()->assertSee($allFood[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_menu_all_food(): void
    {
        $menu = Menu::factory()->create();
        $data = Food::factory()
            ->make([
                'menu_id' => $menu->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.menus.all-food.store', $menu),
            $data
        );

        $this->assertDatabaseHas('food', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $food = Food::latest('id')->first();

        $this->assertEquals($menu->id, $food->menu_id);
    }
}
