<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Time Selection -->
    <div class="glass-panel p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">1. Pilih Waktu Peminjaman</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-input-label for="selectedDate" :value="__('Tanggal')" />
                <x-text-input wire:model.live="selectedDate" id="selectedDate" type="date" class="block mt-1 w-full" />
            </div>
            <div>
                <x-input-label for="startTime" :value="__('Jam Mulai')" />
                <x-text-input wire:model.live="startTime" id="startTime" type="time" class="block mt-1 w-full" />
            </div>
            <div>
                <x-input-label for="endTime" :value="__('Jam Selesai')" />
                <x-text-input wire:model.live="endTime" id="endTime" type="time" class="block mt-1 w-full" />
            </div>
        </div>
    </div>

    <!-- Building Selection (Tabs) -->
    <div class="glass-panel p-6">
        <div class="flex items-center justify-between mb-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex space-x-8">
                @foreach($buildings as $building)
                    <button wire:click="selectBuilding({{ $building->id }})" 
                            class="pb-4 text-sm font-medium transition-colors border-b-2 {{ $selectedBuildingId == $building->id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        {{ $building->name }}
                    </button>
                @endforeach
            </div>
            
            <!-- Legend -->
            <div class="flex items-center space-x-4 pb-4">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 rounded bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 shadow-sm"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Kosong</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 rounded bg-red-500 border border-red-600 shadow-sm opacity-50"></div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Penuh (Booked)</span>
                </div>
            </div>
        </div>

        <!-- Room Grid (Cinema Style) -->
        <div class="space-y-8">
            @foreach($floors as $floorLevel => $floorRooms)
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700/50"></div>
                    </div>
                    <div class="relative flex justify-start">
                        <span class="pr-3 bg-white/80 dark:bg-dark-900/80 backdrop-blur-lg text-sm font-semibold text-gray-500 dark:text-gray-400">
                            Lantai {{ $floorLevel }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @foreach($floorRooms as $room)
                        @php
                            $isBooked = in_array($room->id, $bookedRoomIds);
                        @endphp
                        <button 
                            wire:click="{{ $isBooked ? "viewBooking({$room->id})" : "selectRoom({$room->id})" }}"
                            @class([
                                'relative p-4 rounded-xl border text-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
                                'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md hover:-translate-y-1 cursor-pointer' => !$isBooked,
                                'bg-red-100 dark:bg-red-900/20 border-red-200 dark:border-red-800/30 opacity-60 cursor-pointer hover:bg-red-200 dark:hover:bg-red-900/40' => $isBooked,
                                'ring-2 ring-indigo-500 scale-105 shadow-lg' => $confirmingRoomId == $room->id
                            ])>
                            
                            <div class="font-bold {{ $isBooked ? 'text-red-700 dark:text-red-400' : 'text-gray-800 dark:text-gray-200' }}">
                                {{ $room->name }}
                            </div>
                            <div class="text-xs mt-1 {{ $isBooked ? 'text-red-500 dark:text-red-500/70' : 'text-gray-500 dark:text-gray-400' }}">
                                Kapasitas: {{ $room->capacity }}
                            </div>

                            @if($isBooked)
                                <div class="absolute inset-0 flex justify-center items-center">
                                    <svg class="w-8 h-8 text-red-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <!-- Booking Confirmation Modal -->
    <x-modal name="booking-modal" focusable>
        <form wire:submit.prevent="confirmBooking" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Konfirmasi Peminjaman
            </h2>

            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block">Tanggal</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Waktu</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $startTime }} - {{ $endTime }}</span>
                    </div>
                </div>
            </div>

            <!-- Title / Purpose -->
            <div class="mt-4">
                <x-input-label for="bookingPurpose" :value="__('Tujuan Peminjaman (Acara/Mata Kuliah)')" />
                <x-text-input wire:model="bookingPurpose" id="bookingPurpose" class="block mt-1 w-full" type="text" required autofocus placeholder="Contoh: Kelas Pengganti Algoritma" />
                <x-input-error :messages="$errors->get('bookingPurpose')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button wire:click="$set('confirmingRoomId', null)" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Pesan Ruangan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- View Booking Info Modal -->
    <x-modal name="view-booking-modal" focusable>
        @if($viewingBooking)
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Informasi Ruangan
                    </h2>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $viewingBooking['status'] === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($viewingBooking['status']) }}
                    </span>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Peminjam</span>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $viewingBooking['user']['name'] }}</div>
                    </div>
                    
                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Acara / Mata Kuliah</span>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $viewingBooking['title'] }}</div>
                    </div>

                    <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Waktu Booking</span>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($viewingBooking['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($viewingBooking['end_time'])->format('H:i') }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button wire:click="$set('viewingBooking', null)" x-on:click="$dispatch('close')">
                        {{ __('Tutup') }}
                    </x-secondary-button>
                </div>
            </div>
        @endif
    </x-modal>
</div>
