<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContractOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $countLanjut = DB::table('contracts')->where('status_kontrak', 'Selesai')->count();
        $countPending = DB::table('contracts')->where('status_kontrak', 'In Progress')->count();
        $countBatal = DB::table('contracts')->where('status_kontrak', 'Batal')->count();

        return [
            Stat::make('Successful Contracts', $countLanjut),
            Stat::make('Ongoing Contracts', $countPending),
            Stat::make('Cancelled Contracts', $countBatal),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
