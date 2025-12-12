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

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Analytics\Widgets\KpiOverview::class,
             \App\Filament\Analytics\Widgets\TasksCreatedTrend::class,
        ];
    }
}
