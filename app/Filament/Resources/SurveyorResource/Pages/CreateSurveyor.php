<?php

namespace App\Filament\Resources\SurveyorResource\Pages;

use App\Filament\Resources\SurveyorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyor extends CreateRecord
{
    protected static string $resource = SurveyorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
