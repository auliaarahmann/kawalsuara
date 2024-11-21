<?php

namespace App\Filament\Widgets;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;
use App\Models\Vote;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 4,
    ];

    public static function canView(): bool
    {
        // Role 'saksi' tidak menampilkan widget
        return auth()->user()->role !== 'saksi';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Kecamatan', Kecamatan::count())
                ->description('Total jumlah kecamatan')
                ->descriptionIcon('heroicon-m-building-library')
                ->chart([100, 3, 5, 9, 15, 20, 17])
                ->color('info'),   

            Stat::make('Jumlah Desa', Kelurahan::count())
                ->description('Total jumlah Desa / Gampong')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([1, 3, 5, 9, 15, 20, 17])
                ->color('info'),   

            Stat::make('Jumlah TPS', Tps::count())
                ->description('Total jumlah TPS')
                ->descriptionIcon('heroicon-m-map-pin')
                ->chart([1, 3, 5, 9, 15, 20, 17])
                ->color('info'),

            Stat::make('Data belum diverifikasi', Vote::query()
                ->where('status', 'unverified')
                ->count())
                ->description('Data masuk belum diverifikasi')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([1, 3, 5, 9, 15, 20, 17])
                ->color('warning'),   
                        
                                                
        ];
    }
}
