<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Menu;

use App\Models\Client;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_menus(): void
    {
        $menus = Menu::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('menus.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.menus.index')
            ->assertViewHas('menus');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_menu(): void
    {
        $response = $this->get(route('menus.create'));

        $response->assertOk()->assertViewIs('app.menus.create');
    }

    /**
     * @test
     */
    public function it_stores_the_menu(): void
    {
        $data = Menu::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('menus.store'), $data);

        $this->assertDatabaseHas('menus', $data);

        $menu = Menu::latest('id')->first();

        $response->assertRedirect(route('menus.edit', $menu));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_menu(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->get(route('menus.show', $menu));

        $response
            ->assertOk()
            ->assertViewIs('app.menus.show')
            ->assertViewHas('menu');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_menu(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->get(route('menus.edit', $menu));

        $response
            ->assertOk()
            ->assertViewIs('app.menus.edit')
            ->assertViewHas('menu');
    }

    /**
     * @test
     */
    public function it_updates_the_menu(): void
    {
        $menu = Menu::factory()->create();

        $client = Client::factory()->create();

        $data = [
            'drink_list' => $this->faker->text(255),
            'dessert_list' => $this->faker->text(255),
            'food_list' => $this->faker->text(255),
            'client_id' => $client->id,
        ];

        $response = $this->put(route('menus.update', $menu), $data);

        $data['id'] = $menu->id;

        $this->assertDatabaseHas('menus', $data);

        $response->assertRedirect(route('menus.edit', $menu));
    }

    /**
     * @test
     */
    public function it_deletes_the_menu(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->delete(route('menus.destroy', $menu));

        $response->assertRedirect(route('menus.index'));

        $this->assertModelMissing($menu);
    }
}
