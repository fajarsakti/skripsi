<?php

namespace App\Filament\Resources\SurveyorResource\Pages;

use App\Filament\Resources\SurveyorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyor extends EditRecord
{
    protected static string $resource = SurveyorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
