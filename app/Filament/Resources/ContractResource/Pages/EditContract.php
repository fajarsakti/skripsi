<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function afterSave(): void
    {
        $contract = $this->record;

        if ($contract->status_kontrak == 'Batal') {
            $pemberiTugas = $contract->pemberi_tugas;
            $contractId = $contract->id;

            $recipients = User::where('role', 'admin')->get();

            foreach ($recipients as $recipient) {
                $recipient->notify(
                    Notification::make()
                        ->title('Kontrak telah dibatalkan')  // change the title here
                        ->body("Kontrak dengan ID $contractId dari $pemberiTugas telah dibatalkan")  // change the body message here
                        ->actions([
                            Action::make('View')
                                ->button()
                                ->url(ContractResource::getUrl('view', ['record' => $contract]))
                        ])
                        ->toDatabase($recipients)
                );
            }
        } elseif ($contract->status_kontrak == 'Selesai') {
            $pemberiTugas = $contract->pemberi_tugas;
            $contractId = $contract->id;

            $recipients = User::where('role', 'admin')->get();

            foreach ($recipients as $recipient) {
                $recipient->notify(
                    Notification::make()
                        ->title('Kontrak telah selesai')
                        ->body("Kontrak dengan ID $contractId dari $pemberiTugas sudah selesai")
                        ->actions([
                            Action::make('View')
                                ->button()
                                ->url(route('contract.pdf', ['id' => $contract->id]))
                                ->openUrlInNewTab()
                        ])
                        ->toDatabase($recipients)
                );
            }
        }
    }
}
