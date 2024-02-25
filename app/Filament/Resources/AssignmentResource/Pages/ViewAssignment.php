<?php

namespace App\Filament\Resources\AssignmentResource\Pages;

use App\Filament\Resources\AssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\ButtonAction;

class ViewAssignment extends ViewRecord
{
    protected static string $resource = AssignmentResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['id'] = auth()->id();

        return $data;
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('View pdf')
                ->url(fn ($record) => route('assignment.pdf', $record->id))
                ->openUrlInNewTab(),
        ];
    }
}
