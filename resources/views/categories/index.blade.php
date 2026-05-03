<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            @if ($categories->isNotEmpty())
               <a
                href="{{ route('categories.create') }}"
                class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-200 dark:text-gray-900 dark:hover:bg-white dark:focus:ring-offset-gray-800"
            >
                New Category
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

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                @if ($categories->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/60">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Created</th>
                                                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Updated</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $category->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $category->created_at?->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $category->updated_at?->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            <div class="flex justify-end gap-3">
                                                <a
                                                    href="{{ route('categories.edit', $category) }}"
                                                    class="text-sm font-medium text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                >
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="text-sm  cursor-pointer font-medium text-red-600 transition hover:text-red-500 dark:text-red-400 dark:hover:text-red-300"
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
                        {{ $categories->links() }}
                    </div>
                @else
                    <div class="p-8 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">No categories yet</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Create your first category to organize tasks.</p>
                        <a
                            href="{{ route('categories.create') }}"
                            class="mt-4 inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-200 dark:text-gray-900 dark:hover:bg-white dark:focus:ring-offset-gray-800"
                        >
                            Create Category
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
