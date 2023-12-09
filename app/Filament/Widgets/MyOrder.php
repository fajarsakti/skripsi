<?php

namespace App\Filament\Widgets;

use COM;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
// use Filament\Actions\Modal\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use App\Models\Contract;
use App\Models\User;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;

class MyOrder extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

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
                    ->where('status_kontrak', 'In Progress'),
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

                        $recipients = User::whereIn('role', ['admin','debitur'])->get();

                        foreach ($recipients as $recipient) {
                            $recipient->notify(
                                Notification::make()
                                    ->title('Kontrak telah dibatalkan')
                                    ->danger()
                                    ->send()
                                    ->body("Kontrak dengan ID $contractId dari $pemberiTugas telah dibatalkan")
                                    ->actions([
                                        NotificationAction::make('View')
                                            ->button()
                                            ->url(ContractResource::getUrl('view', ['record' => $record]))
                                    ])
                                    ->toDatabase($recipients)
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Batalkan order ini?')
                    ->modalSubmitActionLabel('Ya, batalkan order')
            ]);
    }


    public static function canView(): bool
    {
        return auth()->user()->role == 'debitur';
    }
}
