<?php


namespace Tests\Feature;

use App\Models\Contractor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_contractors()
    {
        Contractor::factory()->count(3)->create();

        $response = $this->getJson('/api/contractors');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_contractor()
    {
        $contractorData = [
            'user_id' => $this->user->id,
            'specialty' => 'Electrician',
            'availability' => 'full-time',
        ];

        $response = $this->postJson('/api/contractors', $contractorData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contractors', $contractorData);
    }

    /** @test */
    public function it_can_update_a_contractor()
    {
        $contractor = Contractor::factory()->create(['user_id' => $this->user->id]);
        $updateData = ['specialty' => 'Plumber'];

        $response = $this->putJson("/api/contractors/{$contractor->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contractors', array_merge(['id' => $contractor->id], $updateData));
    }

    /** @test */
    public function it_can_delete_a_contractor()
    {
        $contractor = Contractor::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/contractors/{$contractor->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contractors', ['id' => $contractor->id]);
    }
}
