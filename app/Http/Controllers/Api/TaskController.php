<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    /**
     * User's tasks.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // get user tasks
        $tasks = Auth::user()->tasks()->latest()->get();

        // Return response
        return response()->json([
            'success' => true,
            'data' => $tasks
        ], 200);
    }

    /**
     * Store new task.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'is_completed' => 'boolean',
        ]);

        // Create new task record
        $task = Auth::user()->tasks()->create($validated);

        // Return successful response
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data'    => $task->only(['id', 'title', 'body', 'is_completed', 'user_id', 'created_at', 'updated_at']),
        ], 201);
    }

    /**
     * Show the specified task.
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        // Check if the authenticated user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Return successful response
        return response()->json([
            'success' => true,
            'data' => $task
        ], 200);
    }

    /**
     * Update the specified task.
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        // Check if the authenticated user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Validate data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'is_completed' => 'boolean',
        ]);

        // Update task with validated data
        $task->update($validated);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data' => $task
        ], 200);
    }

    /**
     * Remove the specified task.
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        // Check if the authenticated user owns the task
        if ($task->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Delete the task
        $task->delete();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ], 200);
    }

}
