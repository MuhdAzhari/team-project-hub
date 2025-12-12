<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\Task;
use Filament\Widgets\PieChartWidget;

final class TaskStatusDistribution extends PieChartWidget
{
    protected static ?string $heading = 'Task Status Distribution';

    protected static ?string $description = 'Distribution of tasks by current status';


    protected function getData(): array
    {
        $user = auth()->user();

        $query = Task::query()
            ->selectRaw('status, COUNT(*) as c')
            ->groupBy('status');

        if ($user?->role === 'member') {
            $query->where('assigned_to', $user->id);
        }

        $rows = $query->get()->keyBy('status');

        $order = ['todo', 'in_progress', 'done'];

        $data = array_map(
            fn (string $status) => (int) ($rows[$status]->c ?? 0),
            $order
        );

        return [
            'labels' => ['To do', 'In Progress', 'Done'],
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        '#fc3838ff', // Red - Todo
                        '#F59E0B', // Amber - In Progress
                        '#10B981', // Green - Done
                    ],
                    'borderColor' => '#FFFFFF',
                    'borderWidth' => 2,
                ],
            ],
        ];

    }
}
