<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p><strong>Contact Person:</strong> {{ $client->contact_person ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $client->email ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $client->phone ?? '-' }}</p>
                    <p><strong>Notes:</strong> {{ $client->notes ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-lg">Projects</h3>
                        <a href="{{ route('projects.create') }}"
                           class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + New Project
                        </a>
                    </div>

                    @if ($client->projects->isEmpty())
                        <p class="text-gray-500">No projects for this client yet.</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-3 py-2 text-left">Name</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Start</th>
                                    <th class="px-3 py-2 text-left">End</th>
                                    <th class="px-3 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->projects as $project)
                                    <tr class="border-b">
                                        <td class="px-3 py-2">
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="text-blue-600 hover:underline">
                                                {{ $project->name }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-2">{{ ucfirst($project->status) }}</td>
                                        <td class="px-3 py-2">{{ $project->start_date }}</td>
                                        <td class="px-3 py-2">{{ $project->end_date }}</td>
                                        <td class="px-3 py-2 text-right">
                                            <a href="{{ route('projects.edit', $project) }}"
                                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </a>
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
