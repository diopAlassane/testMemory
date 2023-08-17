<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Dessert;

use App\Models\Menu;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DessertControllerTest extends TestCase
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
    public function it_displays_index_view_with_desserts(): void
    {
        $desserts = Dessert::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('desserts.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.desserts.index')
            ->assertViewHas('desserts');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_dessert(): void
    {
        $response = $this->get(route('desserts.create'));

        $response->assertOk()->assertViewIs('app.desserts.create');
    }

    /**
     * @test
     */
    public function it_stores_the_dessert(): void
    {
        $data = Dessert::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('desserts.store'), $data);

        $this->assertDatabaseHas('desserts', $data);

        $dessert = Dessert::latest('id')->first();

        $response->assertRedirect(route('desserts.edit', $dessert));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_dessert(): void
    {
        $dessert = Dessert::factory()->create();

        $response = $this->get(route('desserts.show', $dessert));

        $response
            ->assertOk()
            ->assertViewIs('app.desserts.show')
            ->assertViewHas('dessert');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_dessert(): void
    {
        $dessert = Dessert::factory()->create();

        $response = $this->get(route('desserts.edit', $dessert));

        $response
            ->assertOk()
            ->assertViewIs('app.desserts.edit')
            ->assertViewHas('dessert');
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

        $response = $this->put(route('desserts.update', $dessert), $data);

        $data['id'] = $dessert->id;

        $this->assertDatabaseHas('desserts', $data);

        $response->assertRedirect(route('desserts.edit', $dessert));
    }

    /**
     * @test
     */
    public function it_deletes_the_dessert(): void
    {
        $dessert = Dessert::factory()->create();

        $response = $this->delete(route('desserts.destroy', $dessert));

        $response->assertRedirect(route('desserts.index'));

        $this->assertModelMissing($dessert);
    }
}
