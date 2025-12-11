<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task: ') . $task->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 text-red-800 px-4 py-2 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" value="{{ old('title', $task->title) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach (['todo', 'in_progress', 'done'] as $status)
                                        <option value="{{ $status }}"
                                            @selected(old('status', $task->status) == $status)>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority</label>
                                <select name="priority"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach (['low', 'medium', 'high'] as $priority)
                                        <option value="{{ $priority }}"
                                            @selected(old('priority', $task->priority) == $priority)>
                                            {{ ucfirst($priority) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date"
                                       value="{{ old('due_date', $task->due_date) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assignee</label>
                            <select name="assigned_to"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Unassigned --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @selected(old('assigned_to', $task->assigned_to) == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('projects.show', $project) }}"
                               class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Update Task
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

