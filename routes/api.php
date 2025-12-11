<?php

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ProjectResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/projects', function () {
    $projects = Project::with('client')
        ->latest()
        ->paginate(10);

    return ProjectResource::collection($projects);
});

Route::get('/projects/{project}', function (Project $project) {
    $project->load(['client', 'tasks.assignee']);

    return new ProjectResource($project);
});

Route::get('/projects/{project}/tasks', function (Project $project) {
    $project->load('tasks.assignee');

    return TaskResource::collection($project->tasks);
});