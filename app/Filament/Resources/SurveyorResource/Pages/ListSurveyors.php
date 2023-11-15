<?php

namespace App\Filament\Resources\SurveyorResource\Pages;

use App\Filament\Resources\SurveyorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyors extends ListRecords
{
    protected static string $resource = SurveyorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
