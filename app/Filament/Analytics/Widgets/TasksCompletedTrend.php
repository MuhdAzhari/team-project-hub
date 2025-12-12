<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

final class TasksCompletedTrend extends LineChartWidget
{
    protected static ?string $heading = 'Tasks Completed';

    protected function getData(): array
    {
        $user = auth()->user();

        $filters = \App\Support\AnalyticsFilters::get();

        $fromLabel = Carbon::parse($filters['date_from'])->format('d M Y');
        $toLabel   = Carbon::parse($filters['date_to'])->format('d M Y');

        static::$heading = "Tasks Created ({$fromLabel} – {$toLabel})";

        $from = Carbon::parse($filters['date_from'])->startOfDay();
        $to   = Carbon::parse($filters['date_to'])->addDay()->startOfDay(); // exclusive upper bound

        $query = Task::query()
            ->selectRaw('DATE(updated_at) as d, COUNT(*) as c')
            ->where('status', 'done')
            ->where('updated_at', '>=', $from)
            ->where('updated_at', '<', $to)
            ->groupBy(DB::raw('DATE(updated_at)'))
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
                    'label' => 'Completed',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
