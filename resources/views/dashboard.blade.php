<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pusat Kendali Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Banner -->
            <div class="glass-panel p-8 rounded-2xl bg-gradient-to-r from-primary-600 to-accent-600 text-white shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 bg-white/10 opacity-50 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-primary-100 text-lg">Siap untuk beraktivitas hari ini? Jangan lupa cek jadwal kelas Anda.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('bookings.index') }}" class="px-6 py-3 bg-white text-primary-700 font-bold rounded-full shadow-lg hover:bg-gray-50 transition transform hover:scale-105 inline-block">
                            Booking Ruangan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="glass-panel p-6 rounded-2xl border-l-4 border-indigo-500 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Booking</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalBookings }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="glass-panel p-6 rounded-2xl border-l-4 border-emerald-500 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Diskusi Forum</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalThreads }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl">
                            <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="glass-panel p-6 rounded-2xl border-l-4 border-rose-500 hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Campus Feed</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalPosts }}</p>
                        </div>
                        <div class="p-3 bg-rose-100 dark:bg-rose-900/50 rounded-xl">
                            <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                <!-- Upcoming Bookings -->
                <div class="glass-panel p-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Jadwal Kelas & Ruangan</h4>
                        <a href="{{ route('bookings.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">Lihat Semua &rarr;</a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($upcomingBookings as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex flex-col items-center justify-center text-indigo-700 dark:text-indigo-400">
                                        <span class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($booking->start_time)->translatedFormat('M') }}</span>
                                        <span class="text-lg font-extrabold leading-none">{{ \Carbon\Carbon::parse($booking->start_time)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $booking->purpose }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $booking->room->name }}</p>
                                    <p class="text-xs font-semibold px-2 py-1 bg-{{ $booking->status == 'approved' ? 'green' : ($booking->status == 'pending' ? 'yellow' : 'red') }}-100 text-{{ $booking->status == 'approved' ? 'green' : ($booking->status == 'pending' ? 'yellow' : 'red') }}-800 rounded-full inline-block mt-1">
                                        {{ ucfirst($booking->status) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada jadwal peminjaman ruangan dalam waktu dekat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Feed -->
                <div class="glass-panel p-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Terbaru</h4>
                        <a href="{{ route('feed.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">Lihat Feed &rarr;</a>
                    </div>
                    
                    <div class="space-y-5">
                        @forelse($recentPosts as $post)
                            <div class="flex space-x-3 group">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-accent-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl flex-1 border border-transparent group-hover:border-primary-200 transition-colors">
                                    <div class="flex justify-between items-center mb-1">
                                        <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ $post->user->name }}</h5>
                                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $post->content }}</p>
                                    <div class="mt-2 flex space-x-4 text-xs text-gray-500">
                                        <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg> {{ $post->likes->count() }}</span>
                                        <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg> {{ $post->comments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Belum ada aktivitas di Campus Feed.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
