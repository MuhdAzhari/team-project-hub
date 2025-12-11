<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

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

        ActivityLog::forTask(
            $task,
            'task_created',
            "Task '{$task->title}' was created in project '{$task->project->name}'.",
            $data
        );

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

        $original = $task->getOriginal();

        $task->update($data);

        $changes = [];

        if ($original['status'] !== $task->status) {
            $changes['status'] = [
                'old' => $original['status'],
                'new' => $task->status,
            ];
        }

        if ($original['assigned_to'] !== $task->assigned_to) {
            $changes['assigned_to'] = [
                'old' => $original['assigned_to'],
                'new' => $task->assigned_to,
            ];
        }

        foreach (['title', 'priority', 'due_date'] as $field) {
            if ($original[$field] != $task->{$field}) {
                $changes[$field] = [
                    'old' => $original[$field],
                    'new' => $task->{$field},
                ];
            }
        }

        ActivityLog::forTask(
            $task,
            'task_updated',
            "Task '{$task->title}' was updated.",
            $changes ?: null
        );

        return redirect()
            ->route('projects.show', $task->project_id)
            ->with('success', 'Task updated successfully.');
    }


    public function destroy(Task $task): RedirectResponse
    {
        $projectId = $task->project_id;

        ActivityLog::forTask(
            $task,
            'task_deleted',
            "Task '{$task->title}' was deleted."
        );


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

        ActivityLog::forTask(
            $task,
            'task_status_changed',
            "Status of task '{$task->title}' changed from '{$oldStatus}' to '{$task->status}'.",
            ['status' => ['old' => $oldStatus, 'new' => $task->status]]
        );

        return redirect()
            ->route('projects.show', $task->project_id)
            ->with('success', 'Task status updated.');
    }
}
