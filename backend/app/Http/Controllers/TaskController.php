<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{

    /**
     * Retrieve all tasks in descending order of their creation time.
     *
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing the list of tasks or an error message.
     */
    public function index()
    {
        try {
            $tasks = Task::orderBy('created_at', 'desc')->get();
            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve tasks'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Returns a JSON response containing the newly created task or an error message.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $task = Task::create($validated);
            return response()->json($task, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     * Returns a JSON response containing the updated task or an error message.
     */
    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'completed' => 'sometimes|boolean',
            ]);

            $task->update($validated);
            return response()->json($task);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    /**
     * Remove the specified task from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * Returns a JSON response with HTTP status 204 on success or an error message on failure.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
    }
}