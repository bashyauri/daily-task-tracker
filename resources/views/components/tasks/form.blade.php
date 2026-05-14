@props([
    'action',
    'method' => 'POST',
    'task' => null,
    'categories',
    'submitLabel' => 'Save Task',
])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div class="md:col-span-2">
            <x-input-label for="title" :value="__('Task Title')" />
            <x-text-input
                id="title"
                name="title"
                type="text"
                class="mt-1 block w-full"
                :value="old('title', $task?->title)"
                required
                autofocus
                autocomplete="off"
                placeholder="e.g. Finish quarterly report"
            />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="category_id" :value="__('Category')" />
            <x-select-input id="category_id" name="category_id" class="mt-1 block w-full">
                <option value="">No category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $task?->category_id) === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="task_date" :value="__('Task Date')" />
            <x-text-input
                id="task_date"
                name="task_date"
                type="date"
                class="mt-1 block w-full"
                :value="old('task_date', $task?->task_date?->format('Y-m-d'))"
            />
            <x-input-error :messages="$errors->get('task_date')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea
            id="description"
            name="description"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
            placeholder="Add context, subtasks, or a reminder"
        >{{ old('description', $task?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('tasks.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
        >
            Cancel
        </a>

        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>
    </div>
</form>
