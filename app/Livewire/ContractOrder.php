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

    public $surveys_id;

    public $surveyors_id;

    public $pemberi_tugas;

    public $industries_id;

    public $contract_types_id;

    public $lokasi_proyek;

    public $tanggal_kontrak;

    public $assets_id;

    public $selesai_kontrak;

    public $status_kontrak;

    public $durasi_kontrak;

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
        $data = $this->form->getState();

        $contract_order = Contract::create($data);

        $this->form->model($contract_order)->saveRelationships();

        $pemberiTugas = $contract_order->pemberi_tugas;

        $recipients = User::where('role', 'admin')->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(
                Notification::make()
                    ->title('Kontrak baru telah ditambahkan')
                    ->send()
                    ->warning()
                    ->body("Terdapat kontrak baru dari $pemberiTugas yang harus dilakukan survey")
                    ->actions([
                        Action::make('View')
                            ->button()
                            ->url(ContractResource::getUrl('view', ['record' => $contract_order]))
                            ->openUrlInNewTab()
                    ])
                    ->toDatabase($recipients)
            );
        }

        return redirect()->route('order-message');
    }

    public function render()
    {
        return view('livewire.contract-order');
    }
}
