<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Project info --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p><strong>Client:</strong> {{ $project->client?->name ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
                    <p><strong>Start Date:</strong> {{ $project->start_date ?? '-' }}</p>
                    <p><strong>End Date:</strong> {{ $project->end_date ?? '-' }}</p>
                    <p><strong>Description:</strong> {{ $project->description ?? '-' }}</p>

                    <div class="mt-4">
                        <a href="{{ route('projects.edit', $project) }}"
                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Edit Project
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kanban board --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-lg">Tasks (Kanban)</h3>
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                           class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Add Task
                        </a>
                    </div>

                    @php
                        $todo = $project->tasks->where('status', 'todo');
                        $inProgress = $project->tasks->where('status', 'in_progress');
                        $done = $project->tasks->where('status', 'done');
                    @endphp

                    @if ($project->tasks->isEmpty())
                        <p class="text-gray-500">No tasks for this project yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- TODO column --}}
                            <div>
                                <h4 class="font-semibold mb-2">To Do</h4>
                                <div class="space-y-3">
                                    @foreach ($todo as $task)
                                        <div class="border rounded-md p-3 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-semibold">{{ $task->title }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        Priority: {{ ucfirst($task->priority) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Due: {{ $task->due_date ?? '-' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex justify-between items-center">
                                                <div class="space-x-1">
                                                    <form action="{{ route('tasks.update-status', $task) }}"
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                            Move to In Progress
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="space-x-1">
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                       class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('Delete this task?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- In Progress column --}}
                            <div>
                                <h4 class="font-semibold mb-2">In Progress</h4>
                                <div class="space-y-3">
                                    @foreach ($inProgress as $task)
                                        <div class="border rounded-md p-3 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-semibold">{{ $task->title }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        Priority: {{ ucfirst($task->priority) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Due: {{ $task->due_date ?? '-' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex justify-between items-center">
                                                <div class="space-x-1">
                                                    <form action="{{ route('tasks.update-status', $task) }}"
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="todo">
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">
                                                            Move to To Do
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('tasks.update-status', $task) }}"
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                            Move to Done
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="space-x-1">
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                       class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('Delete this task?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Done column --}}
                            <div>
                                <h4 class="font-semibold mb-2">Done</h4>
                                <div class="space-y-3">
                                    @foreach ($done as $task)
                                        <div class="border rounded-md p-3 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="font-semibold">{{ $task->title }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        Priority: {{ ucfirst($task->priority) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Due: {{ $task->due_date ?? '-' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex justify-between items-center">
                                                <div class="space-x-1">
                                                    <form action="{{ route('tasks.update-status', $task) }}"
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                            Move to In Progress
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="space-x-1">
                                                    <a href="{{ route('tasks.edit', $task) }}"
                                                       class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('Delete this task?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
