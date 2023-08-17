<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Drink;

use App\Models\Menu;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DrinkControllerTest extends TestCase
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
    public function it_displays_index_view_with_drinks(): void
    {
        $drinks = Drink::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('drinks.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.drinks.index')
            ->assertViewHas('drinks');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_drink(): void
    {
        $response = $this->get(route('drinks.create'));

        $response->assertOk()->assertViewIs('app.drinks.create');
    }

    /**
     * @test
     */
    public function it_stores_the_drink(): void
    {
        $data = Drink::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('drinks.store'), $data);

        $this->assertDatabaseHas('drinks', $data);

        $drink = Drink::latest('id')->first();

        $response->assertRedirect(route('drinks.edit', $drink));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_drink(): void
    {
        $drink = Drink::factory()->create();

        $response = $this->get(route('drinks.show', $drink));

        $response
            ->assertOk()
            ->assertViewIs('app.drinks.show')
            ->assertViewHas('drink');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_drink(): void
    {
        $drink = Drink::factory()->create();

        $response = $this->get(route('drinks.edit', $drink));

        $response
            ->assertOk()
            ->assertViewIs('app.drinks.edit')
            ->assertViewHas('drink');
    }

    /**
     * @test
     */
    public function it_updates_the_drink(): void
    {
        $drink = Drink::factory()->create();

        $menu = Menu::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'image' => $this->faker->text(255),
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'menu_id' => $menu->id,
        ];

        $response = $this->put(route('drinks.update', $drink), $data);

        $data['id'] = $drink->id;

        $this->assertDatabaseHas('drinks', $data);

        $response->assertRedirect(route('drinks.edit', $drink));
    }

    /**
     * @test
     */
    public function it_deletes_the_drink(): void
    {
        $drink = Drink::factory()->create();

        $response = $this->delete(route('drinks.destroy', $drink));

        $response->assertRedirect(route('drinks.index'));

        $this->assertModelMissing($drink);
    }
}
