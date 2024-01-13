<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Http\Controllers\OrderPDFController;
use App\Mail\OrderCompletedMail;
use App\Models\Contract;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Mail;

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

        if ($contract->status_kontrak == 'Selesai') {
            $pemberiTugas = $contract->pemberi_tugas;
            $contractId = $contract->id;

            $recipients = User::where('role', 'debitur')->get();

            $pdfController = new OrderPDFController();

            foreach ($recipients as $recipient) {

                $pdf = $pdfController->contractpdf($contract->id);

                Mail::to($recipient->email)
                    ->send(new OrderCompletedMail($pdf));

                $recipient->notify(
                    Notification::make()
                        ->title('Order telah selesai')
                        ->success()
                        ->body("Order dengan ID $contractId dari $pemberiTugas sudah selesai. Silahkan cek email anda")
                        ->toDatabase($recipient)
                );
            }
        }
    }
}
