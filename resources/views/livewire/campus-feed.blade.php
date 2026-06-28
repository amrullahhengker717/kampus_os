<div class="space-y-6">
    <!-- Create Post Glass Panel -->
    <div class="glass-panel p-6 animate-fade-in">
        <form wire:submit.prevent="createPost">
            <textarea 
                wire:model.defer="content" 
                rows="3" 
                class="w-full bg-white/50 dark:bg-dark-900/50 border-gray-300 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500" 
                placeholder="Apa yang sedang terjadi di kampus?"
                required></textarea>
            @error('content') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            
            <div class="mt-4 mb-4">
                <input type="file" wire:model="attachments" multiple class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-400 mt-1" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                @error('attachments.*') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4 flex justify-between items-center">
                <div class="flex space-x-2">
                    <select wire:model.defer="type" class="text-sm border-gray-300 dark:border-gray-700 bg-white/50 dark:bg-dark-900/50 rounded-lg text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        <option value="general">Umum</option>
                        <option value="news">Berita</option>
                        <option value="event">Acara</option>
                        <option value="marketplace">Jual Beli</option>
                    </select>
                </div>
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-gradient-to-r from-primary-500 to-accent-500 hover:from-primary-600 hover:to-accent-600 text-white font-semibold rounded-full shadow-md transition-all duration-200 transform hover:scale-105 disabled:opacity-50">
                    <span wire:loading.remove wire:target="createPost">Posting</span>
                    <span wire:loading wire:target="createPost">Mengunggah...</span>
                </button>
            </div>
        </form>
        @if (session()->has('message'))
            <div class="mt-3 text-sm text-green-600 dark:text-green-400">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Feed Timeline -->
    <div class="space-y-4">
        @forelse($posts as $post)
            <div class="glass-panel p-6 animate-slide-up" style="animation-delay: {{ $loop->index * 50 }}ms;">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
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
                    @if($post->user_id === auth()->id())
                        <button wire:click="deletePost({{ $post->id }})" wire:confirm="Yakin ingin menghapus postingan ini?" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    @endif
                </div>
                
                <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $post->content }}</div>
                
                <!-- Attachments Display -->
                @if($post->attachments->count() > 0)
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($post->attachments as $attachment)
                            @if(str_starts_with($attachment->mime_type, 'image/'))
                                <img src="{{ Storage::url($attachment->file_path) }}" class="rounded-lg object-cover w-full h-48 border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90" onclick="window.open(this.src, '_blank')">
                            @endif
                        @endforeach
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50 flex space-x-6 text-sm text-gray-500 dark:text-gray-400">
                    <button wire:click="toggleLike({{ $post->id }})" class="flex items-center space-x-1 {{ $post->likes->contains('user_id', auth()->id()) ? 'text-red-500' : 'hover:text-red-500' }} transition-colors">
                        <svg class="w-5 h-5 {{ $post->likes->contains('user_id', auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span>{{ $post->likes_count }} Suka</span>
                    </button>
                    <button wire:click="toggleComments({{ $post->id }})" class="flex items-center space-x-1 hover:text-primary-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span>{{ $post->comments_count }} Komentar</span>
                    </button>
                </div>

                <!-- Comments Section -->
                @if(in_array($post->id, $activeComments))
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 animate-fade-in">
                        <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            @forelse($post->comments as $comment)
                                <div class="flex space-x-3 text-sm">
                                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl px-4 py-2 flex-1">
                                        <div class="font-bold text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</div>
                                        <div class="text-gray-700 dark:text-gray-300">{{ $comment->body }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-xs text-gray-500">Belum ada komentar.</div>
                            @endforelse
                        </div>

                        <!-- Add Comment Form -->
                        <form wire:submit.prevent="addComment({{ $post->id }})" class="flex items-center space-x-2">
                            <input wire:model.defer="commentBodies.{{ $post->id }}" type="text" class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500 rounded-full shadow-sm text-sm" placeholder="Tulis komentar...">
                            <button type="submit" class="p-2 rounded-full bg-primary-100 text-primary-600 hover:bg-primary-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="glass-panel p-8 text-center text-gray-500 dark:text-gray-400">
                Belum ada postingan. Jadilah yang pertama membagikan sesuatu!
            </div>
        @endforelse
    </div>
    
    <div class="mt-6 pb-12">
        {{ $posts->links() }}
    </div>
</div>
