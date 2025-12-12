<?php

namespace App\Filament\Analytics\Pages;

use Filament\Pages\Page;

final class AnalyticsDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $title = 'Analytics Dashboard';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.analytics.pages.analytics-dashboard';

    public function getFilters(): array
    {
        return \App\Support\AnalyticsFilters::get();
    }

    public function updateFilters(array $filters): void
    {
        \App\Support\AnalyticsFilters::set($filters);
    }

    public function mount(): void
    {
        if (request()->hasAny(['range', 'date_from', 'date_to', 'project_id'])) {
            \App\Support\AnalyticsFilters::set([
                'range'      => request('range', '30'),
                'date_from'  => request('date_from'),
                'date_to'    => request('date_to'),
                'project_id' => request('project_id'),
            ]);
        }
    }



    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Analytics\Widgets\KpiOverview::class,
            // \App\Filament\Analytics\Widgets\TasksCreatedTrend::class,
            // \App\Filament\Analytics\Widgets\TasksCompletedTrend::class,
            // \App\Filament\Analytics\Widgets\TaskStatusDistribution::class,
            // \App\Filament\Analytics\Widgets\ActivityActionsByDay::class,
            // \App\Filament\Analytics\Widgets\RecentActivityTable::class,
        ];
    }



}
