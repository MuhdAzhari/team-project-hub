<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

final class ActivityActionsByDay extends LineChartWidget
{
    protected static ?string $heading = 'Activity Actions';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getData(): array
    {
        $filters = \App\Support\AnalyticsFilters::get();

        $from = Carbon::parse($filters['date_from'])->startOfDay();
        $to   = Carbon::parse($filters['date_to'])->addDay()->startOfDay(); // exclusive upper bound

        $rows = ActivityLog::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $from)
            ->where('created_at', '<', $to)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $labels = [];
        $data   = [];

        $days = collect();
        $cursor = $from->copy();

        while ($cursor->lt($to)) {
            $days->push($cursor->toDateString());
            $cursor->addDay();
        }

        foreach ($days as $d) {
            $labels[] = Carbon::parse($d)->format('d M');
            $data[]   = (int) ($rows[$d]->c ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Actions',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
