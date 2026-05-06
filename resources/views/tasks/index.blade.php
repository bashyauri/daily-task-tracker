<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ __('Tasks') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track daily work, filter what matters, and update completion in place.</p>
            </div>

            @if ($tasks->isNotEmpty())
                <a
                    href="{{ route('tasks.create') }}"
                    class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-200 dark:text-gray-900 dark:hover:bg-white dark:focus:ring-offset-gray-800"
                >
                    New Task
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <x-tasks.filters :categories="$categories" :filters="$filters" />

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                @if ($tasks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/60">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Recurring</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 align-top">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $task->title }}</div>
                                            @if ($task->description)
                                                <p class="mt-1 max-w-xl text-sm text-gray-600 dark:text-gray-300">{{ $task->description }}</p>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $task->category?->name ?? 'Uncategorized' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $task->task_date?->format('Y-m-d') ?? 'No date' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <x-tasks.status-badge :completed="$task->completed_at !== null" />
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $task->is_recurring ? 'Yes' : 'No' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap justify-end gap-3 text-sm">
                                                <form method="POST" action="{{ route('tasks.toggle-completion', $task) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="cursor-pointer font-medium text-emerald-600 transition hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300"
                                                    >
                                                        {{ $task->completed_at ? 'Mark Incomplete' : 'Mark Complete' }}
                                                    </button>
                                                </form>

                                                <a
                                                    href="{{ route('tasks.edit', $task) }}"
                                                    class="font-medium text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                >
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="cursor-pointer font-medium text-red-600 transition hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                        {{ $tasks->links() }}
                    </div>
                @else
                    <div class="p-8 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">No tasks found</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            {{ collect($filters)->filter()->isNotEmpty() ? 'Adjust the current filters or clear them to see more tasks.' : 'Create your first task to start tracking daily work.' }}
                        </p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a
                                href="{{ route('tasks.create') }}"
                                class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-200 dark:text-gray-900 dark:hover:bg-white dark:focus:ring-offset-gray-800"
                            >
                                Create Task
                            </a>
                            @if (collect($filters)->filter()->isNotEmpty())
                                <a
                                    href="{{ route('tasks.index') }}"
                                    class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
                                >
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
