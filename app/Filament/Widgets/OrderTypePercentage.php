<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderTypePercentage extends ChartWidget
{
    protected static ?string $heading = 'Order Type Percentages';

    protected static ?string $maxHeight = '250px';

    // public ?string $filter = 'all';

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
        // $selectedSurveyors = $this->filter;

        // Menggunakan query untuk menghitung jumlah tipe order
        $totalContractTypes = DB::table('contracts')->count();

        // Menggunakan query untuk menghitung jumlah setiap tipe order
        $query = DB::table('contracts')
            ->join('contract_types', 'contract_types.id', '=', 'contracts.contract_types_id')
            // ->join('surveyors', 'surveyors_id', '=', 'contracts.surveyors_id')
            ->select('contract_types.type', DB::raw('COUNT(*) as count'))
            ->groupBy('contract_types.type');


        // if ($selectedSurveyors != 'all') {
        //     $query = $query->where('surveyors.id', $selectedSurveyors);
        // }

        $data = $query->get();

        $labels = [];
        $percentages = [];
        foreach ($data as $item) {
            $labels[] = $item->type;
            $percentage = ($item->count / $totalContractTypes) * 100;
            $percentages[] = round($percentage, 2);
        }

        // dd($data);

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Tujuan Order',
                    'data' => $percentages,
                    'backgroundColor' => ['#FF5733', '#33FF57', '#5733FF', '#FFFF00'], // Warna untuk setiap tipe order
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    // protected function getFilters(): ?array
    // {
    //     // $surveyors = DB::table('surveyors')->pluck('name')->toArray();
    //     // return array_merge(['all' => 'All'], $surveyors);
    // }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
