<x-filament-panels::page>
    @php($filters = $this->getFilters())

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">Filters</h2>

            <div class="text-xs text-gray-500">
                Current: {{ $filters['date_from'] }} → {{ $filters['date_to'] }}
            </div>
        </div>

        <form method="get" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Date Range</label>
                    <select
                        name="range"
                        class="filament-forms-input w-full"
                        onchange="this.form.submit()"
                    >
                        <option value="7"  @selected($filters['range']==='7')>Last 7 days</option>
                        <option value="30" @selected($filters['range']==='30')>Last 30 days</option>
                        <option value="90" @selected($filters['range']==='90')>Last 90 days</option>
                        <option value="custom" @selected($filters['range']==='custom')>Custom</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">From</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ $filters['date_from'] }}"
                        class="filament-forms-input w-full"
                        @disabled($filters['range']!=='custom')
                    />
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">To</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ $filters['date_to'] }}"
                        class="filament-forms-input w-full"
                        @disabled($filters['range']!=='custom')
                    />
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2">
                <x-filament::button type="submit">
                    Apply
                </x-filament::button>

                <a
                    href="{{ request()->url() }}"
                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Snapshot --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Snapshot</h2>
        <x-filament-widgets::widgets :widgets="[
            \App\Filament\Analytics\Widgets\KpiOverview::class,
        ]" :columns="3" />
    </div>

    {{-- Trends --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Trends</h2>
        <x-filament-widgets::widgets :widgets="[
            \App\Filament\Analytics\Widgets\TasksCreatedTrend::class,
            \App\Filament\Analytics\Widgets\TasksCompletedTrend::class,
        ]" :columns="2" />
    </div>

    {{-- Distribution --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-3">Distribution</h2>
        <x-filament-widgets::widgets :widgets="[
            \App\Filament\Analytics\Widgets\TaskStatusDistribution::class,
        ]" :columns="2" />
    </div>

    {{-- Admin only --}}
    @if(auth()->user()?->role === 'admin')
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-3">Admin Activity</h2>
            <x-filament-widgets::widgets :widgets="[
                \App\Filament\Analytics\Widgets\ActivityActionsByDay::class,
                \App\Filament\Analytics\Widgets\RecentActivityTable::class,
            ]" :columns="2" />
        </div>
    @endif
</x-filament-panels::page>
