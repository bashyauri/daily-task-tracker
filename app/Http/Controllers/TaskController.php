<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $filters = [
            'status' => $request->string('status')->toString(),
            'category' => $request->string('category')->toString(),
            'from' => $request->string('from')->toString(),
            'to' => $request->string('to')->toString(),
        ];

        $tasks = $user->tasks()
            ->with(['category:id,name,uuid'])
            ->when(
                $filters['status'] === 'completed',
                fn ($query) => $query->whereNotNull('completed_at')
            )
            ->when(
                $filters['status'] === 'incomplete',
                fn ($query) => $query->whereNull('completed_at')
            )
            ->when(
                $filters['category'] !== '',
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) => $categoryQuery->where('uuid', $filters['category'])
                )
            )
            ->when(
                $filters['from'] !== '',
                fn ($query) => $query->whereDate('task_date', '>=', $filters['from'])
            )
            ->when(
                $filters['to'] !== '',
                fn ($query) => $query->whereDate('task_date', '<=', $filters['to'])
            )
            ->orderByDesc('task_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'categories' => $user->categories()->orderBy('name')->get(['id', 'name', 'uuid']),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        return view('tasks.create', [
            'categories' => $request->user()->categories()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->tasks()->create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Request $request, Task $task): View
    {

        $task = TaskResource::make($task);

        return view('tasks.edit', [
            'task' => $task,
            'categories' => $request->user()->categories()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task = TaskResource::make($task);
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->deleteOrFail();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function toggleCompletion(Task $task): RedirectResponse
    {
        $wasCompleted = $task->completed_at !== null;

        $task->update([
            'completed_at' => $wasCompleted ? null : now(),
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', $wasCompleted ? 'Task marked as incomplete.' : 'Task marked as complete.');
    }
}
