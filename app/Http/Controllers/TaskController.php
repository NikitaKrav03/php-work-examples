<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{

    public function index() //GET
    {
        $tasks = Task::all();
        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'Список пуст'], 404);
        }
        return response()->json($tasks->toArray());
    }

    public function store(Request $request) //POST
    {
        try {
            $validated = $request->validate([
                '*.title' => 'required|string|max:127',
                '*.description' => 'nullable|string|max:255',
                '*.status' => 'nullable|string|in:pending,in_progress,completed,canceled'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Данные не корректны',
                'errors' => $e->errors()
            ], 422);
        }

        $createdTasks = [];

        foreach ($validated as $taskData) {
            $task = new Task();
            $task->title = $taskData['title'];
            $task->description = $taskData['description'] ?? null;
            $task->status = $taskData['status'] ?? 'pending';
            $task->save();

            $createdTasks[] = $task;
        }

        return response()->json([
            'message' => 'Задач создано: '. count($createdTasks),
            'tasks' => $createdTasks
        ]);
    }

    public function show(string $id) //GET с id
    {
        $task = Task::query()->find($id);
        if (!$task) {
            return response()->json([
                'message' => 'Задача с id ' . $id . ' не найдена'
            ], 404);
        }
        return response()->json($task->toArray());
    }

    public function update(Request $request, string $id) //PUT с id
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:127',
                'description' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:pending,in_progress,completed,canceled'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Данные не корректны',
                'errors' => $e->errors()
            ], 422);
        }

        $task = Task::query()->find($id);
        if (empty($task)) {
            return response()->json(['message' => 'Задача с id ' . $id . ' отсутствует'], 404);
        }
        $task->fill($validated);
        $task->save();

        return response()->json([
            'message' => 'Задача ' . $id . ' изменена: ',
            'task' => $task->toArray()
        ]);
    }

    public function destroy(string $id) //DELETE с id
    {
        $task = Task::query()->find($id);
        if (empty($task)) {
            return response()->json(['message' => 'Задача с id ' . $id . ' отсутствует'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Задача с id ' . $id . ' удалена']);
    }
}
