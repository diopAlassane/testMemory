<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Paiement;

use App\Models\Client;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaiementControllerTest extends TestCase
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
    public function it_displays_index_view_with_paiements(): void
    {
        $paiements = Paiement::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('paiements.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.paiements.index')
            ->assertViewHas('paiements');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_paiement(): void
    {
        $response = $this->get(route('paiements.create'));

        $response->assertOk()->assertViewIs('app.paiements.create');
    }

    /**
     * @test
     */
    public function it_stores_the_paiement(): void
    {
        $data = Paiement::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('paiements.store'), $data);

        $this->assertDatabaseHas('paiements', $data);

        $paiement = Paiement::latest('id')->first();

        $response->assertRedirect(route('paiements.edit', $paiement));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $response = $this->get(route('paiements.show', $paiement));

        $response
            ->assertOk()
            ->assertViewIs('app.paiements.show')
            ->assertViewHas('paiement');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $response = $this->get(route('paiements.edit', $paiement));

        $response
            ->assertOk()
            ->assertViewIs('app.paiements.edit')
            ->assertViewHas('paiement');
    }

    /**
     * @test
     */
    public function it_updates_the_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $client = Client::factory()->create();

        $data = [
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'print_pdf' => $this->faker->text(255),
            'client_id' => $client->id,
        ];

        $response = $this->put(route('paiements.update', $paiement), $data);

        $data['id'] = $paiement->id;

        $this->assertDatabaseHas('paiements', $data);

        $response->assertRedirect(route('paiements.edit', $paiement));
    }

    /**
     * @test
     */
    public function it_deletes_the_paiement(): void
    {
        $paiement = Paiement::factory()->create();

        $response = $this->delete(route('paiements.destroy', $paiement));

        $response->assertRedirect(route('paiements.index'));

        $this->assertModelMissing($paiement);
    }
}
