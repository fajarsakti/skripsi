<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssetPercentage extends ChartWidget
{
    // public ?string $filter = 'all';

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
        // $selectedSurveyors = $this->filter;

        // Menggunakan query untuk menghitung jumlah total aset
        $totalAset = DB::table('surveys')->count();

        // Menggunakan query untuk menghitung jumlah setiap jenis aset
        $query = DB::table('surveys')
            ->join('assets', 'assets.id', '=', 'surveys.assets_id')
            // ->join('surveyors', 'surveyors.id', '=', 'surveys.surveyors_id')
            ->select('assets.type', DB::raw('COUNT(*) as count'))
            ->groupBy('assets.type');

        // if ($selectedSurveyors != 'all') {
        //     $query = $query->where('surveyors.name', $selectedSurveyors);
        // }

        $data = $query->get();

        $labels = [];
        $percentages = [];
        foreach ($data as $item) {
            $labels[] = $item->type;
            $percentage = ($item->count / $totalAset) * 100;
            $percentages[] = round($percentage, 2);
        }

        // dd($data);

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

    // protected function getFilters(): ?array
    // {
    //     $surveyors = DB::table('surveyors')->pluck('name')->toArray();
    //     return array_merge(['all' => 'All'], $surveyors);
    // }

    protected function getType(): string
    {
        return 'pie';
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
