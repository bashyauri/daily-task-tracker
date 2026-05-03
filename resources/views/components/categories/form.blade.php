@props([
    'action',
    'method' => 'POST',
    'category' => null,
    'submitLabel' => 'Save Category',
])

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <x-input-label for="name" :value="__('Category Name')" />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            :value="old('name', $category?->name)"
            required
            autofocus
            autocomplete="off"
            placeholder="e.g. Work, Personal, Health"
        />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3">
        <a
            href="{{ route('categories.index') }}"
            class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800"
        >
            Cancel
        </a>

        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>
    </div>
</form>
