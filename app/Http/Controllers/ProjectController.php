<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ActivityLog;


class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('client')->latest()->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id'   => ['required', 'exists:clients,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:planned,active,on_hold,completed'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        Project::create($data);

        ActivityLog::forProject(
            $project,
            'project_created',
            "Project '{$project->name}' was created.",
            $data
        );


        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'tasks.assignee']);

        $taskIds = $project->tasks->pluck('id');

        $activityLogs = ActivityLog::with('user')
            ->where(function ($q) use ($project, $taskIds) {
                $q->where('project_id', $project->id)
                ->orWhereIn('task_id', $taskIds);
            })
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('projects.show', compact('project', 'activityLogs'));
    }




    public function edit(Project $project): View
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'client_id'   => ['nullable', 'exists:clients,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:planned,active,on_hold,completed'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $original = $project->getOriginal();

        $project->update($data);

        $changes = [];
        foreach (['name', 'status', 'start_date', 'end_date'] as $field) {
            if ($original[$field] != $project->{$field}) {
                $changes[$field] = [
                    'old' => $original[$field],
                    'new' => $project->{$field},
                ];
            }
        }

        ActivityLog::forProject(
            $project,
            'project_updated',
            "Project '{$project->name}' was updated.",
            $changes ?: null
        );

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }


    public function destroy(Project $project): RedirectResponse
    {

        ActivityLog::forProject(
            $project,
            'project_deleted',
            "Project '{$project->name}' was deleted."
        );

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
