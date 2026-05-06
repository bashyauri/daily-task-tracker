@props([
    'categories',
    'filters' => [
        'status' => '',
        'category' => '',
        'from' => '',
        'to' => '',
    ],
])

<form method="GET" action="{{ route('tasks.index') }}" class="grid gap-4 rounded-xl border border-gray-200 bg-gray-50/80 p-4 dark:border-gray-700 dark:bg-gray-900/40 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_auto]">
    <div>
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status" class="mt-1 block w-full">
            <option value="">All statuses</option>
            <option value="incomplete" @selected($filters['status'] === 'incomplete')>Incomplete</option>
            <option value="completed" @selected($filters['status'] === 'completed')>Complete</option>
        </x-select-input>
    </div>

    <div>
        <x-input-label for="category" :value="__('Category')" />
        <x-select-input id="category" name="category" class="mt-1 block w-full">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->uuid }}" @selected($filters['category'] === $category->uuid)>
                    {{ $category->name }}
                </option>
            @endforeach
        </x-select-input>
    </div>

    <div>
        <x-input-label for="from" :value="__('From')" />
        <x-text-input id="from" name="from" type="date" class="mt-1 block w-full" :value="$filters['from']" />
    </div>

    <div>
        <x-input-label for="to" :value="__('To')" />
        <x-text-input id="to" name="to" type="date" class="mt-1 block w-full" :value="$filters['to']" />
    </div>

    <div class="flex items-end gap-3">
        <x-primary-button class="justify-center">
            Filter
        </x-primary-button>

        <a
            href="{{ route('tasks.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
        >
            Reset
        </a>
    </div>
</form>
