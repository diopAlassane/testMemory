<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Drink;

use App\Models\Menu;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DrinkTest extends TestCase
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
    public function it_gets_drinks_list(): void
    {
        $drinks = Drink::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.drinks.index'));

        $response->assertOk()->assertSee($drinks[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_drink(): void
    {
        $data = Drink::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.drinks.store'), $data);

        $this->assertDatabaseHas('drinks', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(route('api.drinks.update', $drink), $data);

        $data['id'] = $drink->id;

        $this->assertDatabaseHas('drinks', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_drink(): void
    {
        $drink = Drink::factory()->create();

        $response = $this->deleteJson(route('api.drinks.destroy', $drink));

        $this->assertModelMissing($drink);

        $response->assertNoContent();
    }
}
