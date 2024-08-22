<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectBid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectBidControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_bids_for_a_project()
    {
        ProjectBid::factory()->count(3)->create(['project_id' => $this->project->id]);

        $response = $this->getJson("/api/projects/{$this->project->id}/bids");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_new_bid_for_a_project()
    {
        $bidData = [
            'bid_amount' => 1500.00,
        ];

        $response = $this->postJson("/api/projects/{$this->project->id}/bids", $bidData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('project_bids', [
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
            'bid_amount' => 1500.00,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_can_update_an_existing_bid()
    {
        $bid = ProjectBid::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
            'bid_amount' => 1200.00,
            'status' => 'pending',
        ]);

        $updateData = [
            'bid_amount' => 1300.00,
            'status' => 'accepted',
        ];

        $response = $this->putJson("/api/bids/{$bid->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('project_bids', [
            'id' => $bid->id,
            'bid_amount' => 1300.00,
            'status' => 'accepted',
        ]);
    }

    /** @test */
    public function it_can_delete_a_bid()
    {
        $bid = ProjectBid::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->deleteJson("/api/bids/{$bid->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('project_bids', ['id' => $bid->id]);
    }

    /** @test */
    public function it_only_allows_owner_to_update_bid()
    {
        $otherUser = User::factory()->create();
        $bid = ProjectBid::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $otherUser->id,
            'bid_amount' => 1200.00,
            'status' => 'pending',
        ]);

        $updateData = [
            'bid_amount' => 1300.00,
            'status' => 'accepted',
        ];

        $response = $this->putJson("/api/bids/{$bid->id}", $updateData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_only_allows_owner_to_delete_bid()
    {
        $otherUser = User::factory()->create();
        $bid = ProjectBid::factory()->create([
            'project_id' => $this->project->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->deleteJson("/api/bids/{$bid->id}");

        $response->assertStatus(403);
    }
}
