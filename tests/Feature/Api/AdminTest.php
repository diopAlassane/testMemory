<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Admin;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
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
    public function it_gets_admins_list(): void
    {
        $admins = Admin::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.admins.index'));

        $response->assertOk()->assertSee($admins[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_admin(): void
    {
        $data = Admin::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.admins.store'), $data);

        $this->assertDatabaseHas('admins', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_admin(): void
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
        ];

        $response = $this->putJson(route('api.admins.update', $admin), $data);

        $data['id'] = $admin->id;

        $this->assertDatabaseHas('admins', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_admin(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->deleteJson(route('api.admins.destroy', $admin));

        $this->assertModelMissing($admin);

        $response->assertNoContent();
    }
}
