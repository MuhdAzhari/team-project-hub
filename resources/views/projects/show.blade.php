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
                        <h3 class="text-lg font-semibold text-gray-900">Tasks (Kanban)</h3>
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
                        <div id="kanban-board" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- TODO column --}}
                            <div class="kanban-column" data-status="todo">
                                <h4 class="font-semibold mb-2">To Do</h4>
                                <div class="space-y-3 min-h-[50px] kanban-column-body">
                                    @foreach ($todo as $task)
                                        <div class="task-card border rounded-md p-3 bg-gray-50 cursor-move"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="todo">
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

                                            <div class="mt-3 flex justify-end space-x-1">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this task?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- In Progress column --}}
                            <div class="kanban-column" data-status="in_progress">
                                <h4 class="font-semibold mb-2">In Progress</h4>
                                <div class="space-y-3 min-h-[50px] kanban-column-body">
                                    @foreach ($inProgress as $task)
                                        <div class="task-card border rounded-md p-3 bg-gray-50 cursor-move"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="in_progress">
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

                                            <div class="mt-3 flex justify-end space-x-1">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this task?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Done column --}}
                            <div class="kanban-column" data-status="done">
                                <h4 class="font-semibold mb-2">Done</h4>
                                <div class="space-y-3 min-h-[50px] kanban-column-body">
                                    @foreach ($done as $task)
                                        <div class="task-card border rounded-md p-3 bg-gray-50 cursor-move"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="done">
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

                                            <div class="mt-3 flex justify-end space-x-1">
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                class="px-2 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this task?');"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endif

                </div>
            </div>

            {{-- Inline script for drag-and-drop --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let draggedCard = null;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Make task cards draggable
                    document.querySelectorAll('.task-card').forEach(function (card) {
                        card.addEventListener('dragstart', function (e) {
                            draggedCard = this;
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/plain', this.dataset.taskId);
                            this.classList.add('opacity-50');
                        });

                        card.addEventListener('dragend', function () {
                            this.classList.remove('opacity-50');
                            draggedCard = null;
                        });
                    });

                    // Make columns droppable
                    document.querySelectorAll('.kanban-column').forEach(function (column) {
                        const body = column.querySelector('.kanban-column-body');
                        const newStatus = column.dataset.status;

                        column.addEventListener('dragover', function (e) {
                            e.preventDefault();
                            e.dataTransfer.dropEffect = 'move';
                            column.classList.add('bg-gray-100');
                        });

                        column.addEventListener('dragleave', function () {
                            column.classList.remove('bg-gray-100');
                        });

                        column.addEventListener('drop', function (e) {
                            e.preventDefault();
                            column.classList.remove('bg-gray-100');

                            if (!draggedCard) {
                                return;
                            }

                            const taskId = draggedCard.dataset.taskId;

                            // Optimistic UI update: move the card immediately
                            body.appendChild(draggedCard);
                            draggedCard.dataset.status = newStatus;

                            // Send AJAX request to update status on server
                            fetch(`/tasks/${taskId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ status: newStatus })
                            }).then(function (response) {
                                if (!response.ok) {
                                    // If something went wrong, reload to stay consistent
                                    window.location.reload();
                                }
                            }).catch(function () {
                                window.location.reload();
                            });
                        });
                    });
                });
            </script>


        </div>
    </div>
</x-app-layout>
