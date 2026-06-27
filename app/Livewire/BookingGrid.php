<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Building;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingGrid extends Component
{
    public $selectedDate;
    public $startTime = '08:00';
    public $endTime = '10:00';
    public $selectedBuildingId;
    
    public $bookingPurpose = '';
    public $confirmingRoomId = null;
    public $viewingBooking = null;

    public function mount()
    {
        $this->selectedDate = date('Y-m-d');
        $firstBuilding = Building::first();
        if ($firstBuilding) {
            $this->selectedBuildingId = $firstBuilding->id;
        }
    }

    public function selectBuilding($id)
    {
        $this->selectedBuildingId = $id;
        $this->confirmingRoomId = null;
        $this->viewingBooking = null;
    }

    public function getStartDateTime()
    {
        return Carbon::parse($this->selectedDate . ' ' . $this->startTime);
    }

    public function getEndDateTime()
    {
        return Carbon::parse($this->selectedDate . ' ' . $this->endTime);
    }

    public function selectRoom($roomId)
    {
        $this->confirmingRoomId = $roomId;
        $this->bookingPurpose = '';
        $this->dispatch('open-modal', 'booking-modal');
    }

    public function viewBooking($roomId)
    {
        $start = $this->getStartDateTime();
        $end = $this->getEndDateTime();

        $booking = Booking::with('user')
            ->where('room_id', $roomId)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                      });
            })->first();

        if ($booking) {
            $this->viewingBooking = $booking;
            $this->dispatch('open-modal', 'view-booking-modal');
        }
    }

    public function confirmBooking()
    {
        $this->validate([
            'bookingPurpose' => 'required|string|max:255'
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $this->confirmingRoomId,
            'title' => $this->bookingPurpose,
            'start_time' => $this->getStartDateTime(),
            'end_time' => $this->getEndDateTime(),
            'status' => 'pending'
        ]);

        $this->confirmingRoomId = null;
        $this->dispatch('close-modal', 'booking-modal');
        session()->flash('message', 'Booking request submitted successfully!');
    }

    public function render()
    {
        $buildings = Building::all();
        
        $rooms = collect();
        $bookedRoomIds = [];

        if ($this->selectedBuildingId && $this->selectedDate && $this->startTime && $this->endTime) {
            $rooms = Room::where('building_id', $this->selectedBuildingId)
                ->orderBy('floor')
                ->orderBy('name')
                ->get()
                ->groupBy('floor');

            $start = $this->getStartDateTime();
            $end = $this->getEndDateTime();

            $bookedRoomIds = Booking::whereIn('room_id', Room::where('building_id', $this->selectedBuildingId)->pluck('id'))
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_time', [$start, $end])
                          ->orWhereBetween('end_time', [$start, $end])
                          ->orWhere(function ($q) use ($start, $end) {
                              $q->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                          });
                })
                ->pluck('room_id')
                ->toArray();
        }

        return view('livewire.booking-grid', [
            'buildings' => $buildings,
            'floors' => $rooms,
            'bookedRoomIds' => $bookedRoomIds
        ]);
    }
}
