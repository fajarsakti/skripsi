<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IndustryPercentage extends ChartWidget
{
    protected static ?string $heading = 'Industry Type Percentage';

    protected static ?string $maxHeight = '250px';

    public static function getSort(): int
    {
        return static::$sort ?? 1;
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'display' => false,
                ],
                'x' => [
                    'display' => false,
                ]
            ],
        ];
    }

    protected function getData(): array
    {
        // Menggunakan query untuk menghitung jumlah jenis industri
        $totalIndustry = DB::table('contracts')->count();

        // Menggunakan query untuk menghitung jumlah setiap jenis industri
        $data = DB::table('contracts')
            ->join('industries', 'industries.id', '=', 'contracts.industries_id')
            ->select('industries.type', DB::raw('COUNT(*) as count'))
            ->groupBy('industries.type')
            ->get();

        $labels = [];
        $percentages = [];
        foreach ($data as $item) {
            $labels[] = $item->type;
            $percentage = ($item->count / $totalIndustry) * 100;
            $percentages[] = round($percentage, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Tujuan Kontrak',
                    'data' => $percentages,
                    'backgroundColor' => ['#FF5733', '#33FF57', '#5733FF'], // Warna untuk setiap jenis industri
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
