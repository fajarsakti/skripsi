<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderDuration extends ChartWidget
{
    protected static ?string $heading = 'Order Completion Time Average';

    public ?string $filter = 'all';

    public static function getSort(): int
    {
        return static::$sort ?? 1;
    }

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $selectedTime = $this->filter;

        $query = DB::table('contracts')
            ->join('surveyors', 'surveyors.id', '=', 'contracts.surveyors_id')
            ->select(
                'surveyors.name',
                DB::raw('AVG(CASE WHEN status_kontrak = "Selesai" THEN DATEDIFF(selesai_kontrak, tanggal_kontrak) ELSE NULL END) as average_duration')
            )
            ->groupBy('surveyors_id', 'surveyors.name');

        if ($selectedTime == 'today') {
            $query = $query->whereDate('tanggal_kontrak', Carbon::today());
        } elseif ($selectedTime == 'week') {
            $query = $query->whereBetween('tanggal_kontrak', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($selectedTime == 'month') {
            $query = $query->whereMonth('tanggal_kontrak', Carbon::now()->subMonth()->month);
        } elseif ($selectedTime == 'year') {
            $query = $query->whereYear('tanggal_kontrak', Carbon::now()->year);
        }

        $data = $query->get();

        $labels = $data->pluck('name')->toArray();
        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Waktu Penyelesaian Order (dalam hari)',
                    'data' => $data->pluck('average_duration')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                    'hoverBackgroundColor' => 'rgba(75, 192, 192, 0.4)',
                    'hoverBorderColor' => 'rgba(75, 192, 192, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
            'all' => 'All'
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
