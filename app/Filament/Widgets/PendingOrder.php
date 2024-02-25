<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContractResource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use App\Models\Contract;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingOrder extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function terimaOrder($id)
    {
        $order = Contract::find($id);
        $order->status_kontrak = "In Progress";
        $order->is_available = 1;
        $order->save();

        return redirect()->back();
    }

    public function batalkanOrder($id)
    {
        $order = Contract::find($id);
        $order->status_kontrak = "Ditolak";
        $order->is_available = 0;
        $order->save();

        return redirect()->back();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContractResource::getEloquentQuery()
                ->where('status_kontrak', 'Pending'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Contract ID')
                    ->sortable(),
                TextColumn::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('lokasi_proyek')
                    ->label('Lokasi Survey')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('contract_types.type')
                    ->label('Tujuan Kontrak')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('industries.type')
                    ->label('Jenis Industri')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('assets.type')
                    ->label('Jenis Aset')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Action::make('terimaOrder')
                    ->button()
                    ->disabled(function (Contract $record) {
                        return $record->is_available === 1 || $record->status_kontrak !== 'Pending';
                    })
                    ->label('Approve Order')
                    ->action(function ($record) {
                        $this->terimaOrder($record->id);

                        $recipients = User::where('role', 'debitur')->get();
                        foreach ($recipients as $recipient) {
                            $recipient->notify(
                                Notification::make()
                                    ->title('Order telah diterima')
                                    ->success()
                                    ->toDatabase($recipients)
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Terima order ini?')
                    ->modalSubmitActionLabel('Ya, terima order ini'),
                Action::make('batalkanOrder')
                    ->button()
                    ->disabled(function (Contract $record) {
                        return $record->is_available === 1 || $record->status_kontrak !== 'Pending';
                    })
                    ->label('Tolak Order')
                    ->action(function ($record) {
                        $this->batalkanOrder($record->id);

                        $recipients = User::where('role', 'debitur')->get();

                        foreach ($recipients as $recipient) {
                            $recipient->notify(
                                Notification::make()
                                    ->title('Order ditolak')
                                    ->body('Order anda tidak memenuhi kriteria yang dibutuhkan untuk dilanjutkan ke proses survey. Silahkan melakukan order kembali')
                                    ->danger()
                                    ->toDatabase($recipients)
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Tolak order ini?')
                    ->modalSubmitActionLabel('Ya, tolak order ini')
                    ->color('danger')
            ])
            ->emptyStateHeading('No orders yet');
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
