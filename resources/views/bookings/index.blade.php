<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Classroom Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="glass-panel p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Booking Calendar</h3>
                    @can('create bookings')
                    <x-primary-button x-data @click="$dispatch('openBookingModal', { start: new Date().toISOString(), end: new Date().toISOString() })">
                        {{ __('New Booking') }}
                    </x-primary-button>
                    @endcan
                </div>

                <div id="calendar" class="min-h-[600px] text-gray-800 dark:text-gray-200"></div>
            </div>
            
            <livewire:booking-modal />

        </div>
    </div>

    <!-- Initialize FullCalendar -->
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            var calendar = new window.Calendar(calendarEl, {
                plugins: [ window.dayGridPlugin, window.interactionPlugin ],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                editable: true,
                selectable: true,
                select: function(info) {
                    Livewire.dispatch('openBookingModal', [info.startStr, info.endStr]);
                },
                events: [
                    @foreach($bookings as $booking)
                    {
                        title: '{{ $booking->room->name }} - {{ $booking->user->name }}',
                        start: '{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i:s') }}',
                        end: '{{ \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i:s') }}',
                        color: '{{ $booking->status === 'approved' ? '#10b981' : ($booking->status === 'pending' ? '#f59e0b' : '#ef4444') }}'
                    },
                    @endforeach
                ]
            });
            
            calendar.render();

            // Refresh page when booking is created to re-render calendar events
            window.addEventListener('bookingCreated', () => {
                window.location.reload();
            });
        });
    </script>
</x-app-layout>
