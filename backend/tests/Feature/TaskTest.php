<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test : authenticated user can store a task.
     * @return void
     */
    public function test_authenticated_user_can_store_task(): void
    {
        // Create a test user
        $user = User::factory()->create();

        // Sample task data
        $taskData = [
            'title' => 'Test Task',
            'body' => 'This is a test task body.',
            'is_completed' => false,
        ];

        // POST request send to store the task
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', $taskData);

        // Assert response status
        $response->assertStatus(201)
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Task created successfully.',
                'title' => 'Test Task',
                'body' => 'This is a test task body.',
                'is_completed' => false,
            ]);


        // Assert the task is in the database
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test : authenticated user can view their own task.
     * @return void
     */
    public function test_authenticated_user_can_view_own_task(): void
    {
        // Create a test user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // GET request send to show the task
        $response = $this->actingAs($user, 'sanctum')->getJson("/api/tasks/{$task->id}");

        // Assert successful response with task data
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                ]
            ]);
    }

    /**
     * Test : a user cannot view another user's task.
     * @return void
     */
    public function test_user_cannot_view_others_task(): void
    {
        // Create two users and a task belonging to the second user
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        // Try to view the other user's task
        $response = $this->actingAs($user, 'sanctum')->getJson("/api/tasks/{$task->id}");

        // Assert forbidden response
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized access.',
            ]);
    }

    /**
     * Test : a user can update their own task.
     * @return void
     */
    public function test_authenticated_user_can_update_own_task(): void
    {
        // Create a user and a task belonging to them
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Prepare updated data
        $updatedData = [
            'title' => 'Updated Task',
            'body' => 'Updated body content.',
            'is_completed' => true,
        ];

        // PUT request send to update the task
        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", $updatedData);

        // Assert update response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully.',
                'data' => [
                    'title' => 'Updated Task',
                    'is_completed' => true,
                ]
            ]);

        // Ensure updated data is in the database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'is_completed' => true,
        ]);
    }

    /**
     * Test : a user cannot update another user's task.
     * @return void
     */
    public function test_user_cannot_update_others_task(): void
    {
        // Create two users and a task for the second one
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        // Prepare update data
        $updatedData = [
            'title' => 'Should Not Work',
            'body' => 'Blocked update.',
            'is_completed' => false,
        ];

        // Try to update the other user's task
        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", $updatedData);

        // Assert forbidden response
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized access.',
            ]);

        // Ensure task was not updated in the database
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'title' => 'Should Not Work',
        ]);
    }

}
