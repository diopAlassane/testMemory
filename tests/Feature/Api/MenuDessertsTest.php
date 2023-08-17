<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Menu;
use App\Models\Dessert;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuDessertsTest extends TestCase
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
    public function it_gets_menu_desserts(): void
    {
        $menu = Menu::factory()->create();
        $desserts = Dessert::factory()
            ->count(2)
            ->create([
                'menu_id' => $menu->id,
            ]);

        $response = $this->getJson(route('api.menus.desserts.index', $menu));

        $response->assertOk()->assertSee($desserts[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_menu_desserts(): void
    {
        $menu = Menu::factory()->create();
        $data = Dessert::factory()
            ->make([
                'menu_id' => $menu->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.menus.desserts.store', $menu),
            $data
        );

        $this->assertDatabaseHas('desserts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $dessert = Dessert::latest('id')->first();

        $this->assertEquals($menu->id, $dessert->menu_id);
    }
}
