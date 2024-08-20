<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Project;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
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
    public function it_can_list_payments()
    {
        // Create a project for the authenticated user
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        // Create some payments
        Payment::factory()->count(3)->create(['project_id' => $project->id]);

        // Send a GET request to the payment index route
        $response = $this->getJson('/api/payments');

        // Assert that the response is OK and contains the expected data
        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([['id', 'project_id', 'amount', 'status', 'created_at', 'updated_at']]);
    }

    /** @test */
    public function it_can_show_a_single_payment()
    {
        // Create a project and a payment
        $project = Project::factory()->create(['user_id' => $this->user->id]);
        $payment = Payment::factory()->create(['project_id' => $project->id]);

        // Send a GET request to the payment show route
        $response = $this->getJson("/api/payments/{$payment->id}");

        // Assert that the response is OK and contains the expected data
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $payment->id,
                     'project_id' => $payment->project_id,
                     'amount' => $payment->amount,
                     'status' => $payment->status,
                 ]);
    }

    /** @test */
    public function it_can_create_a_payment()
    {
        // Create a project
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        // Data for the new payment
        $paymentData = [
            'project_id' => $project->id,
            'amount' => 150.00,
            'status' => 'pending',
        ];

        // Send a POST request to the payment store route
        $response = $this->postJson('/api/payments', $paymentData);

        // Assert that the response is 201 Created and contains the expected data
        $response->assertStatus(201)
                 ->assertJsonFragment($paymentData);

        // Assert that the payment was created in the database
        $this->assertDatabaseHas('payments', $paymentData);
    }

    /** @test */
    public function it_can_update_a_payment()
    {
        // Create a project and a payment
        $project = Project::factory()->create(['user_id' => $this->user->id]);
        $payment = Payment::factory()->create(['project_id' => $project->id]);

        // Data to update the payment
        $updatedData = [
            'amount' => 200.00,
            'status' => 'completed',
        ];

        // Send a PUT request to the payment update route
        $response = $this->putJson("/api/payments/{$payment->id}", $updatedData);

        // Assert that the response is OK and contains the updated data
        $response->assertStatus(200)
                 ->assertJsonFragment($updatedData);

        // Assert that the payment was updated in the database
        $this->assertDatabaseHas('payments', array_merge(['id' => $payment->id], $updatedData));
    }

    /** @test */
    public function it_can_delete_a_payment()
    {
        // Create a project and a payment
        $project = Project::factory()->create(['user_id' => $this->user->id]);
        $payment = Payment::factory()->create(['project_id' => $project->id]);

        // Send a DELETE request to the payment destroy route
        $response = $this->deleteJson("/api/payments/{$payment->id}");

        // Assert that the response is 204 No Content
        $response->assertStatus(204);

        // Assert that the payment was deleted from the database
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
