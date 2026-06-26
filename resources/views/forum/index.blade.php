<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Forum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="glass-panel p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Discussion Boards</h3>
                    <x-primary-button>
                        {{ __('New Thread') }}
                    </x-primary-button>
                </div>

                <div class="text-gray-500 dark:text-gray-400">
                    Forum features will be implemented shortly.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
