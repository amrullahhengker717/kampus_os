<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" wire:poll.10s>
    <button @click="open = ! open" class="relative p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none transition duration-150 ease-in-out rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($this->notifications->count() > 0)
            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full">
                {{ $this->notifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-80 rounded-md shadow-lg sm:w-96"
         style="display: none;">
        
        <div class="glass-panel py-2 overflow-hidden">
            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Notifications</h3>
                @if($this->notifications->count() > 0)
                    <button wire:click="markAllAsRead" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Mark all as read
                    </button>
                @endif
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-150">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="ml-2 flex-shrink-0 text-gray-400 hover:text-indigo-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        No new notifications
                    </div>
                @endforelse
            </div>
            
            <a href="#" class="block text-center px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                View all notifications
            </a>
        </div>
    </div>
</div>
