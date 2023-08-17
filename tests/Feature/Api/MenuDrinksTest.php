<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Menu;
use App\Models\Drink;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuDrinksTest extends TestCase
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
    public function it_gets_menu_drinks(): void
    {
        $menu = Menu::factory()->create();
        $drinks = Drink::factory()
            ->count(2)
            ->create([
                'menu_id' => $menu->id,
            ]);

        $response = $this->getJson(route('api.menus.drinks.index', $menu));

        $response->assertOk()->assertSee($drinks[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_menu_drinks(): void
    {
        $menu = Menu::factory()->create();
        $data = Drink::factory()
            ->make([
                'menu_id' => $menu->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.menus.drinks.store', $menu),
            $data
        );

        $this->assertDatabaseHas('drinks', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $drink = Drink::latest('id')->first();

        $this->assertEquals($menu->id, $drink->menu_id);
    }
}
