<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

final class TasksCreatedTrend extends LineChartWidget
{
    protected static ?string $heading = 'Tasks Created (Last 30 Days)';

    protected function getData(): array
    {
        $user = auth()->user();

        $query = Task::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d');

        if ($user?->role === 'member') {
            $query->where('assigned_to', $user->id);
        }

        $rows = $query->get()->keyBy('d');

        $labels = [];
        $data   = [];

        $days = collect(range(0, 29))
            ->map(fn (int $i) => Carbon::today()->subDays(29 - $i)->toDateString());

        foreach ($days as $d) {
            $labels[] = Carbon::parse($d)->format('d M');
            $data[]   = (int) ($rows[$d]->c ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Created',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
