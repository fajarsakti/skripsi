<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SurveyOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countSurvey = DB::table('surveys')->count();

        return [
            Stat::make('Survey Count', $countSurvey)
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'surveyor';
    }
}
