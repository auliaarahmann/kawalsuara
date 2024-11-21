<?php

namespace App\Filament\Widgets;

use App\Models\Votes;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class VotesChart extends ChartWidget
{
    protected static ?string $heading = 'Data Perolehan Suara';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '350px';  
    protected static ?int $sort = 1;  

    public function getDescription(): ?string
    {
        return 'Data perolehan suara yang telah terverifikasi sesuai Foto C1 Plano.';
    }    

    public static function canView(): bool
    {
        // Role 'saksi' tidak menampilkan widget
        return auth()->user()->role !== 'saksi';
    }    

    protected function getData(): array
    {
        // Menghitung total suara masing-masing paslon
        $totalPaslon1 = Votes::where('status','verified')->sum('paslon_1_vote');
        $totalPaslon2 = Votes::where('status','verified')->sum('paslon_2_vote');
        $totalPaslon3 = Votes::where('status','verified')->sum('paslon_3_vote');
    
        // Format data untuk chart
        return [
            'datasets' => [
                [
                    'label' => 'Paslon 1',
                    'data' => [$totalPaslon1],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
                [
                    'label' => 'Paslon 2',
                    'data' => [$totalPaslon2],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Paslon 3',
                    'data' => [$totalPaslon3],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
            ],
            'labels' => ['Total Suara'],
        ];
    }
    

    protected function getType(): string
    {
        return 'bar';
    }
}
