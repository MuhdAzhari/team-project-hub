<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p><strong>Client:</strong> {{ $project->client?->name ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                    <p><strong>Start Date:</strong> {{ $project->start_date ?? '-' }}</p>
                    <p><strong>End Date:</strong> {{ $project->end_date ?? '-' }}</p>
                    <p><strong>Description:</strong> {{ $project->description ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-lg">Tasks</h3>
                        {{-- Task creation will be added in the next step --}}
                        <p class="text-sm text-gray-500">
                            Task management UI will be implemented later.
                        </p>
                    </div>

                    @if ($project->tasks->isEmpty())
                        <p class="text-gray-500">No tasks for this project yet.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-3 py-2 text-left">Title</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Priority</th>
                                    <th class="px-3 py-2 text-left">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project->tasks as $task)
                                    <tr class="border-b">
                                        <td class="px-3 py-2">{{ $task->title }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($task->status) }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($task->priority) }}</td>
                                        <td class="px-3 py-2">{{ $task->due_date }}</td>
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
