<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SurveyResource;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['id'] = auth()->id();

        return $data;
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('Export pdf')
                ->url(fn ($record) => route('contract.pdf', $record->id))
                ->openUrlInNewTab(),
            ButtonAction::make('Survey')
                ->url(SurveyResource::getUrl('create'))
                ->openUrlInNewTab()
        ];
    }
}
