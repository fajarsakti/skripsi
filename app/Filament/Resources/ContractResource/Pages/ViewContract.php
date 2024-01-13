<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\AssignmentResource;
use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SurveyResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use App\Models\Contract;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['id'] = auth()->id();

        return $data;
    }

    public function terimaOrder($id)
    {
        $order = Contract::find($id);
        $order->status_kontrak = "In Progress";
        $order->is_available = 1;
        $order->save();

        return redirect()->back();
    }

    protected function getActions(): array
    {
        return [
            ButtonAction::make('Assignment')
                ->url(AssignmentResource::getUrl('create'))
                ->openUrlInNewTab()
                ->disabled(function (Contract $record) {
                    return $record->status_kontrak !== 'In Progress';
                })
                ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
            ButtonAction::make('Survey')
                ->url(SurveyResource::getUrl('create'))
                ->openUrlInNewTab()
                ->disabled(function (Contract $record) {
                    return $record->is_available === 0;
                })
                ->visible(fn (Get $get): bool => auth()->user()->role === 'surveyor'),
            ButtonAction::make('Approve')
                ->disabled(function (Contract $record) {
                    return $record->is_available === 1 || $record->status_kontrak !== 'Pending';
                })
                ->action(function ($record) {
                    $this->terimaOrder($record->id);

                    Notification::make()
                        ->title('Kontrak telah diterima')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalDescription('Terima order ini?')
                ->modalSubmitActionLabel('Ya, terima order ini')
        ];
    }
}
