<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['todo', 'in_progress', 'done'];
        $priorities = ['low', 'medium', 'high'];

        foreach (Project::all() as $project) {
            for ($i = 1; $i <= 6; $i++) {
                Task::create([
                    'project_id' => $project->id,
                    'assigned_to' => rand(1, 3), // random user
                    'title' => "Task $i for {$project->name}",
                    'description' => "Auto-generated seed task",
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => now()->addDays(rand(2, 20)),
                ]);
            }
        }
    }
}
