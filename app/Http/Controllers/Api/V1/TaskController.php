<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskStatusChanged;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks;
        return response()->json($tasks, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Auth::user()->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'status' => true,
            'message' => 'با موفقیت انجام شد',
            'data' => $task,
        ], 200);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_complete' => 'boolean',
        ]);

        $task->update($request->all());

        event(new TaskStatusChanged($task));

        return response()->json([
            'status' => true,
            'message' => 'با موفقیت انجام شد',
            'data' => $task,
        ], 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'با موفقیت انجام شد',
            'data' => $task,
        ], 200);
    }
}
