<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">Update category details.</p>

                    <x-categories.form
                        :action="route('categories.update', $category)"
                        method="PUT"
                        :category="$category"
                        submit-label="Update Category"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
