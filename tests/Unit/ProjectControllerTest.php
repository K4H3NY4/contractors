<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function it_can_list_projects()
    {
        // Create projects for the authenticated user
        Project::factory()->count(3)->create(['user_id' => $this->user->id]);

        // Send a GET request to the project index route
        $response = $this->getJson('/api/projects');

        // Assert that the response is OK and contains the expected data
        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([['id', 'user_id', 'title', 'description', 'status', 'total_cost', 'created_at', 'updated_at']]);
    }

    /** @test */
    public function it_can_show_a_single_project()
    {
        // Create a project
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        // Send a GET request to the project show route
        $response = $this->getJson("/api/projects/{$project->id}");

        // Assert that the response is OK and contains the expected data
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $project->id,
                     'user_id' => $project->user_id,
                     'title' => $project->title,
                     'description' => $project->description,
                     'status' => $project->status,
                     'total_cost' => $project->total_cost,
                 ]);
    }

    /** @test */
    public function it_can_create_a_project()
    {
        // Data for the new project
        $projectData = [
            'title' => 'New Project Title',
            'description' => 'Detailed description of the new project.',
            'status' => 'active',
            'total_cost' => 5000.00,
        ];

        // Send a POST request to the project store route
        $response = $this->postJson('/api/projects', $projectData);

        // Assert that the response is 201 Created and contains the expected data
        $response->assertStatus(201)
                 ->assertJsonFragment($projectData);

        // Assert that the project was created in the database
        $this->assertDatabaseHas('projects', array_merge(['user_id' => $this->user->id], $projectData));
    }

    /** @test */
    public function it_can_update_a_project()
    {
        // Create a project
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        // Data to update the project
        $updatedData = [
            'title' => 'Updated Project Title',
            'description' => 'Updated description of the project.',
            'status' => 'completed',
            'total_cost' => 5500.00,
        ];

        // Send a PUT request to the project update route
        $response = $this->putJson("/api/projects/{$project->id}", $updatedData);

        // Assert that the response is OK and contains the updated data
        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);

        // Assert that the project was updated in the database
        $this->assertDatabaseHas('projects', array_merge(['id' => $project->id], $updatedData));
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        // Create a project
        $project = Project::factory()->create(['user_id' =>$this->user->id]);

        // Send a DELETE request to the project destroy route
        $response = $this->deleteJson("/api/projects/{$project->id}");

        // Assert that the response is 204 No Content
        $response->assertStatus(204);

        // Assert that the project was deleted from the database
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}

