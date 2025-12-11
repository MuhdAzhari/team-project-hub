<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="text-sm text-gray-500">
            Role:
            <span class="font-semibold">
                {{ ucfirst(auth()->user()->role ?? 'member') }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Clients</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $clientCount }}
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Projects</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $projectCount }}
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Tasks</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $taskCount }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tasks by status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Tasks by Status
                        </h3>
                        @php
                            $taskStatuses = ['todo', 'in_progress', 'done'];
                        @endphp

                        <ul class="space-y-2">
                            @foreach ($taskStatuses as $status)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $status));
                                    $count = $tasksByStatus[$status] ?? 0;
                                @endphp
                                <li class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ $label }}</span>
                                    <span class="font-semibold text-gray-900">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Projects by status --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Projects by Status
                        </h3>
                        @php
                            $projectStatuses = ['planned', 'active', 'on_hold', 'completed'];
                        @endphp

                        <ul class="space-y-2">
                            @foreach ($projectStatuses as $status)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $status));
                                    $count = $projectsByStatus[$status] ?? 0;
                                @endphp
                                <li class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ $label }}</span>
                                    <span class="font-semibold text-gray-900">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Recent projects --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Recent Projects
                        </h3>
                        <a href="{{ route('projects.index') }}"
                           class="text-sm text-blue-600 hover:underline">
                            View all projects
                        </a>
                    </div>

                    @if ($recentProjects->isEmpty())
                        <p class="text-sm text-gray-500">No projects yet.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-3 py-2 text-left">Name</th>
                                    <th class="px-3 py-2 text-left">Client</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Start</th>
                                    <th class="px-3 py-2 text-left">End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentProjects as $project)
                                    <tr class="border-b">
                                        <td class="px-3 py-2">
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="text-blue-600 hover:underline">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $project->client?->name ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $project->start_date ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ $project->end_date ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
