<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContractResource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use App\Models\Contract;
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

                        Notification::make()
                            ->title('Kontrak telah diterima')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Terima order ini?')
                    ->modalSubmitActionLabel('Ya, terima order ini')
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
