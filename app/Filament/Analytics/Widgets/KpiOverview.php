<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class KpiOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        $clientsCount = Client::query()->count();

        $projectsCount = Project::query()->count();
        $activeProjectsCount = Project::query()->where('status', 'active')->count();

        $tasksBaseQuery = Task::query();

        if ($user?->role === 'member') {
            $tasksBaseQuery->where('assigned_to', $user->id);
        }

        $tasksCount = (clone $tasksBaseQuery)->count();
        $openTasksCount = (clone $tasksBaseQuery)->whereIn('status', ['todo', 'in_progress'])->count();
        $doneTasksCount = (clone $tasksBaseQuery)->where('status', 'done')->count();

        return [
            Stat::make('Clients', $clientsCount),
            Stat::make('Projects', $projectsCount),
            Stat::make('Active Projects', $activeProjectsCount),
            Stat::make('Tasks', $tasksCount),
            Stat::make('Open Tasks', $openTasksCount),
            Stat::make('Done Tasks', $doneTasksCount),
        ];
    }
}
