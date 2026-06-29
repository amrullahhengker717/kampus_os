<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\User;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Mahasiswa & Staf Terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Peminjaman Menunggu', Booking::where('status', 'pending')->count())
                ->description('Perlu Persetujuan Segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Total Postingan Feed', Post::count())
                ->description('Aktivitas Kampus')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('success'),
        ];
    }
}
