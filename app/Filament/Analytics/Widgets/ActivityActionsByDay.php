<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

final class ActivityActionsByDay extends LineChartWidget
{
    protected static ?string $heading = 'Activity Actions (Last 30 Days)';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getData(): array
    {
        $rows = ActivityLog::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d')
            ->get()
            ->keyBy('d');

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
                    'label' => 'Actions',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
