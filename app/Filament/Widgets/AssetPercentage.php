<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssetPercentage extends ChartWidget
{
    protected static ?string $heading = 'Asset Type Percentage';

    public static function getSort(): int
    {
        return static::$sort ?? 2;
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

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // Menggunakan query untuk menghitung jumlah total aset
        $totalAset = DB::table('surveys')->count();

        // Menggunakan query untuk menghitung jumlah setiap jenis aset
        $data = DB::table('surveys')
            ->join('assets', 'assets.id', '=', 'surveys.assets_id')
            ->select('assets.type', DB::raw('COUNT(*) as count'))
            ->groupBy('assets.type')
            ->get();

        $labels = [];
        $percentages = [];
        foreach ($data as $item) {
            $labels[] = $item->type;
            $percentage = ($item->count / $totalAset) * 100;
            $percentages[] = round($percentage, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Jenis Aset',
                    'data' => $percentages,
                    'backgroundColor' => ['#FF5733', '#33FF57', '#5733FF'], // Warna untuk setiap jenis aset
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
