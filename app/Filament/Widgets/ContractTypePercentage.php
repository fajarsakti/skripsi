<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ContractTypePercentage extends ChartWidget
{
    protected static ?string $heading = 'Contract Type Percentages';

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
        // Menggunakan query untuk menghitung jumlah total aset
        $totalContractTypes = DB::table('contracts')->count();

        // Menggunakan query untuk menghitung jumlah setiap jenis aset
        $data = DB::table('contracts')
            ->join('contract_types', 'contract_types.id', '=', 'contracts.contract_types_id')
            ->select('contract_types.type', DB::raw('COUNT(*) as count'))
            ->groupBy('contract_types.type')
            ->get();

        $labels = [];
        $percentages = [];
        foreach ($data as $item) {
            $labels[] = $item->type;
            $percentage = ($item->count / $totalContractTypes) * 100;
            $percentages[] = round($percentage, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Tujuan Kontrak',
                    'data' => $percentages,
                    'backgroundColor' => ['#FF5733', '#33FF57', '#5733FF', '#FFFF00'], // Warna untuk setiap tujuan kontrak
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
