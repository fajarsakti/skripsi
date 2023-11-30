<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;


class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = auth()->id();

        return $data;
    }

    public function afterCreate(): void
    {
        $contract = $this->record;

        $pemberiTugas = $contract->pemberi_tugas;

        $recipients = User::whereIn('role', ['admin', 'surveyor'])->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(
                Notification::make()
                    ->title('Kontrak baru telah ditambahkan')
                    ->body("Terdapat kontrak baru dari $pemberiTugas yang harus dilakukan survey")
                    ->actions([
                        Action::make('View')
                            ->button()
                            ->url(ContractResource::getUrl('view', ['record' => $contract]))
                            ->openUrlInNewTab()
                    ])
                    ->toDatabase($recipients)
            );
        }
    }
}
