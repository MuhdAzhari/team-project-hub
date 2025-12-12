<?php

namespace App\Filament\Analytics\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

final class RecentActivityTable extends TableWidget
{
    protected static ?string $heading = 'Recent Activity (Admin)';
    protected static ?int $sort = 99;

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLog::query()->latest())
            ->defaultPaginationPageOption(10)
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->default('-'),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('task.title')
                    ->label('Task')
                    ->default('-')
                    ->toggleable(),

                // Safe summary: show short description only (no JSON dump)
                Tables\Columns\TextColumn::make('description')
                    ->label('Summary')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
