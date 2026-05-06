<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">Update task details or move it into a different category.</p>

                    <x-tasks.form
                        :action="route('tasks.update', $task)"
                        :categories="$categories"
                        :task="$task"
                        method="PUT"
                        submit-label="Update Task"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
