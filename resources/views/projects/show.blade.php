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
                    <p><strong>Status:</strong>  <x-status-badge :status="$project->status" /></p>
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
            <div class="bg-white shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tasks (Kanban)</h3>
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Add Task
                        </a>
                    </div>

                    @php
                        $todo       = $project->tasks->where('status', 'todo');
                        $inProgress = $project->tasks->where('status', 'in_progress');
                        $done       = $project->tasks->where('status', 'done');
                    @endphp

                    @if ($project->tasks->isEmpty())
                        <p class="text-gray-500">No tasks for this project yet.</p>
                    @else
                        <div id="kanban-board" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- To Do column --}}
                            <div class="kanban-column rounded-lg bg-sky-50/60 border border-sky-100"
                                data-status="todo">
                                <div class="flex items-center justify-between px-3 pt-3 pb-1">
                                    <h4 class="font-semibold text-sky-900 text-sm uppercase tracking-wide">
                                        To Do
                                    </h4>
                                    <span class="text-xs px-2 py-1 rounded-full bg-sky-100 text-sky-700">
                                        {{ $todo->count() }}
                                    </span>
                                </div>
                                <div class="kanban-column-body space-y-3 min-h-[4rem] px-3 pb-3">
                                    @foreach ($todo as $task)
                                        <div class="task-card border border-sky-100 rounded-md p-3 bg-white cursor-move
                                                    shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="todo">
                                            <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                            <div class="mt-1 text-xs text-gray-500 space-y-1">
                                                <div>
                                                    Priority:
                                                    <x-priority-badge :priority="$task->priority" class="ml-1" />
                                                </div>
                                                <div>
                                                    Due: {{ $task->due_date ?? '-' }}
                                                </div>
                                                <div>
                                                    Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                </div>
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
                            <div class="kanban-column rounded-lg bg-amber-50/60 border border-amber-100"
                                data-status="in_progress">
                                <div class="flex items-center justify-between px-3 pt-3 pb-1">
                                    <h4 class="font-semibold text-amber-900 text-sm uppercase tracking-wide">
                                        In Progress
                                    </h4>
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                                        {{ $inProgress->count() }}
                                    </span>
                                </div>
                                <div class="kanban-column-body space-y-3 min-h-[4rem] px-3 pb-3">
                                    @foreach ($inProgress as $task)
                                        <div class="task-card border border-amber-100 rounded-md p-3 bg-white cursor-move
                                                    shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="in_progress">
                                            <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                            <div class="mt-1 text-xs text-gray-500 space-y-1">
                                                <div>
                                                    Priority:
                                                    <x-priority-badge :priority="$task->priority" class="ml-1" />
                                                </div>
                                                <div>
                                                    Due: {{ $task->due_date ?? '-' }}
                                                </div>
                                                <div>
                                                    Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                </div>
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
                            <div class="kanban-column rounded-lg bg-emerald-50/60 border border-emerald-100"
                                data-status="done">
                                <div class="flex items-center justify-between px-3 pt-3 pb-1">
                                    <h4 class="font-semibold text-emerald-900 text-sm uppercase tracking-wide">
                                        Done
                                    </h4>
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                        {{ $done->count() }}
                                    </span>
                                </div>
                                <div class="kanban-column-body space-y-3 min-h-[4rem] px-3 pb-3">
                                    @foreach ($done as $task)
                                        <div class="task-card border border-emerald-100 rounded-md p-3 bg-white cursor-move
                                                    shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5"
                                            draggable="true"
                                            data-task-id="{{ $task->id }}"
                                            data-status="done">
                                            <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                                            <div class="mt-1 text-xs text-gray-500 space-y-1">
                                                <div>
                                                    Priority:
                                                    <x-priority-badge :priority="$task->priority" class="ml-1" />
                                                </div>
                                                <div>
                                                    Due: {{ $task->due_date ?? '-' }}
                                                </div>
                                                <div>
                                                    Assignee: {{ $task->assignee->name ?? 'Unassigned' }}
                                                </div>
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

            {{-- Drag-and-drop script --}}
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
                            this.classList.add('opacity-60');
                        });

                        card.addEventListener('dragend', function () {
                            this.classList.remove('opacity-60');
                            draggedCard = null;
                        });
                    });

                    // Make columns droppable
                    document.querySelectorAll('.kanban-column').forEach(function (column) {
                        const body      = column.querySelector('.kanban-column-body');
                        const newStatus = column.dataset.status;

                        column.addEventListener('dragover', function (e) {
                            e.preventDefault();
                            e.dataTransfer.dropEffect = 'move';
                            column.classList.add('ring-2', 'ring-blue-300');
                        });

                        column.addEventListener('dragleave', function () {
                            column.classList.remove('ring-2', 'ring-blue-300');
                        });

                        column.addEventListener('drop', function (e) {
                            e.preventDefault();
                            column.classList.remove('ring-2', 'ring-blue-300');

                            if (!draggedCard) {
                                return;
                            }

                            const taskId = draggedCard.dataset.taskId;

                            // Optimistic UI: move card immediately
                            body.appendChild(draggedCard);
                            draggedCard.dataset.status = newStatus;

                            // Update server
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
