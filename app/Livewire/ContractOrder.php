<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Contracts\HasTable;
use Illuminate\Console\Concerns\InteractsWithIO;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Models\User;
use Livewire\Component;


class ContractOrder extends Component implements HasForms
{
    use InteractsWithForms;

    public $pemberi_tugas;

    public $industries_id;

    public $contract_types_id;

    public $lokasi_proyek;

    public function getFormSchema(): array
    {
        return ContractResource::getFormSchema();
    }

    public function getFormModel(): Model|string|null
    {
        return Contract::class;
    }

    public function submit()
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

    public function render()
    {
        return view('livewire.contract-order');
    }
}
