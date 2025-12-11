<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function create(Request $request): View
    {
        $projectId = $request->query('project_id');

        $project = Project::findOrFail($projectId);
        $users   = User::orderBy('name')->get();

        return view('tasks.create', compact('project', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id'  => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:todo,in_progress,done'],
            'priority'    => ['required', 'in:low,medium,high'],
            'due_date'    => ['nullable', 'date'],
        ]);

        Task::create($data);

        return redirect()
            ->route('projects.show', $data['project_id'])
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $project = $task->project;
        $users   = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'project', 'users'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'project_id'  => ['required', 'exists:projects,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:todo,in_progress,done'],
            'priority'    => ['required', 'in:low,medium,high'],
            'due_date'    => ['nullable', 'date'],
        ]);

        $task->update($data);

        return redirect()
            ->route('projects.show', $task->project_id)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        $task->delete();

        return redirect()
            ->route('projects.show', $projectId)
            ->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:todo,in_progress,done'],
        ]);

        $task->update([
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('projects.show', $task->project_id)
            ->with('success', 'Task status updated.');
    }
}
