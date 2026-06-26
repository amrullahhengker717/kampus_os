<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Campus Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Create Post Glass Panel -->
            <div class="glass-panel p-6 animate-fade-in">
                <form method="POST" action="{{ route('feed.store') }}">
                    @csrf
                    <textarea 
                        name="content" 
                        rows="3" 
                        class="w-full bg-white/50 dark:bg-dark-900/50 border-gray-300 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" 
                        placeholder="What's happening on campus?"
                        required></textarea>
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div class="flex space-x-2">
                            <select name="type" class="text-sm border-gray-300 dark:border-gray-700 bg-white/50 dark:bg-dark-900/50 rounded-lg text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="general">General</option>
                                <option value="news">News</option>
                                <option value="event">Event</option>
                                <option value="marketplace">Marketplace</option>
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary-500 to-accent-500 hover:from-primary-600 hover:to-accent-600 text-white font-semibold rounded-full shadow-md transition-all duration-200 transform hover:scale-105">
                            Post
                        </button>
                    </div>
                </form>
            </div>

            <!-- Feed Timeline -->
            <div class="space-y-4">
                @forelse($posts as $post)
                    <div class="glass-panel p-6 animate-slide-up" style="animation-delay: {{ $loop->index * 100 }}ms;">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-accent-500 flex items-center justify-center text-white font-bold">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $post->user->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }} &bull; 
                                    <span class="px-2 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-full text-[10px] uppercase font-bold tracking-wider">
                                        {{ $post->type }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $post->content }}</div>
                        
                        <!-- Actions (Like/Comment placeholders) -->
                        <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50 flex space-x-6 text-sm text-gray-500 dark:text-gray-400">
                            <button class="flex items-center space-x-1 hover:text-primary-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span>Like</span>
                            </button>
                            <button class="flex items-center space-x-1 hover:text-primary-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span>Comment</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="glass-panel p-8 text-center text-gray-500 dark:text-gray-400">
                        No posts yet. Be the first to share something!
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
