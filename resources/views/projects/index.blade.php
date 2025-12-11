<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('projects.create') }}"
                   class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + New Project
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="px-3 py-2 text-left">Name</th>
                                <th class="px-3 py-2 text-left">Client</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-left">Start</th>
                                <th class="px-3 py-2 text-left">End</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                                <tr class="border-b">
                                    <td class="px-3 py-2">
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="text-blue-600 hover:underline">
                                            {{ $project->name }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2">{{ $project->client?->name }}</td>
                                    <td class="px-3 py-2">{{ ucfirst($project->status) }}</td>
                                    <td class="px-3 py-2">{{ $project->start_date }}</td>
                                    <td class="px-3 py-2">{{ $project->end_date }}</td>
                                    <td class="px-3 py-2 text-right space-x-2">
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Edit
                                        </a>
                                        <form action="{{ route('projects.destroy', $project) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                        No projects found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
