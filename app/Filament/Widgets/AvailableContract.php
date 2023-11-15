<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
use Filament\Tables\Actions\Action;
use App\Models\Survey;
use App\Models\Contract;

class AvailableContract extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ContractResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Contract ID')
                    ->sortable(),
                TextColumn::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->sortable(),
                TextColumn::make('lokasi_proyek')
                    ->label('Lokasi Survey')
                    ->sortable(),
                TextColumn::make('tujuan_kontrak')
                    ->label('Tujuan Kontrak')
                    ->sortable(),
                TextColumn::make('jenis_industri')
                    ->label('Jenis Industri')
                    ->sortable(),
                ToggleColumn::make('is_available')
                    ->label('Availability'),
            ])
            ->actions([
                Action::make('take_contract')
                    ->button(function (Contract $record) {
                        $record->is_available = false;
                        $record->status_kontrak = 'In Progress';
                        $record->save();
                    })
                    ->label('Ambil Kontrak'),
                // ->url(function (Survey $record) {
                //     route('/admin/surveys' . $record->id . '/edit', ['surveys', 'record' => $this->id]);
                // })
            ]);
    }
}
