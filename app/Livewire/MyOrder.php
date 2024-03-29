<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Filament\Resources\ContractResource;
use Filament\Tables\Columns\TextColumn;
// use Filament\Actions\Action;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\Contract;

class MyOrder extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    public function setStatusOrderBatal($id)
    {
        $order = Contract::find($id);
        $order->status_kontrak = "Batal";
        $order->save();

        return redirect()->back();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContractResource::getEloquentQuery()
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lokasi_proyek')
                    ->label('Lokasi Survey')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contract_types.type')
                    ->label('Tujuan Order')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('industries.type')
                    ->label('Jenis Industri')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_kontrak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'In Progress' => 'warning',
                        'Batal' => 'danger',
                        'Pending' => 'Pending'
                    })
                    ->sortable()
                    ->label('Status Order')
                    ->default('In Progress'),
            ])
            ->actions([
                Action::make('setStatusOrderBatal')
                    ->button()
                    ->color('danger')
                    ->label('Batakan Order')
                    ->action(function ($record) {
                        $this->setStatusOrderBatal($record->id);

                        // Send the notification
                        $pemberiTugas = $record->pemberi_tugas;
                        $contractId = $record->id;

                        $recipients = User::where('role', 'admin')->get();

                        foreach ($recipients as $recipient) {
                            $recipient->notify(
                                Notification::make()
                                    ->title('Order telah dibatalkan')
                                    ->danger()
                                    ->send()
                                    ->body("Order dengan ID $contractId dari $pemberiTugas telah dibatalkan")
                                    ->actions([
                                        NotificationAction::make('View')
                                            ->button()
                                            ->url(ContractResource::getUrl('view', ['record' => $record]))
                                    ])
                                    ->toDatabase($recipients)
                            );
                        }
                    })
                    ->visible(function (Contract $record) {
                        return $record->status_kontrak === 'In Progress';
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Batalkan order ini?')
                    ->modalSubmitActionLabel('Ya, batalkan order')
            ])
            ->emptyStateHeading('No orders yet');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role == 'debitur';
    }

    public function render()
    {
        return view('livewire.my-order');
    }
}
