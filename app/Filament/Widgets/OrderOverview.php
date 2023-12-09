<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $countLanjut = DB::table('contracts')->where('status_kontrak', 'Selesai')->count();
        $countPending = DB::table('contracts')->where('status_kontrak', 'In Progress')->count();
        $countBatal = DB::table('contracts')->where('status_kontrak', 'Batal')->count();

        return [
            Stat::make('Successful Orders', $countLanjut)
                ->color('success'),
            Stat::make('Ongoing Orders', $countPending)
                ->color('warning'),
            Stat::make('Cancelled Orders', $countBatal)
                ->color('danger'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
