<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

final class TasksCreatedTrend extends LineChartWidget
{
    protected static ?string $heading = 'Tasks Created';

    protected function getData(): array
    {
        $user = auth()->user();

        $filters = \App\Support\AnalyticsFilters::get();

        $from = Carbon::parse($filters['date_from'])->startOfDay();
        $to   = Carbon::parse($filters['date_to'])->addDay()->startOfDay(); // exclusive upper bound

        $query = Task::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $from)
            ->where('created_at', '<', $to)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d');

        if ($user?->role === 'member') {
            $query->where('assigned_to', $user->id);
        }

        $rows = $query->get()->keyBy('d');

        $labels = [];
        $data   = [];

        $cursor = $from->copy();
        while ($cursor->lt($to)) {
            $d = $cursor->toDateString();

            $labels[] = $cursor->format('d M');
            $data[]   = (int) ($rows[$d]->c ?? 0);

            $cursor->addDay();
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
