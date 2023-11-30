<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class FinishedContract extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public static function getSort(): int
    {
        return static::$sort ?? 2;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContractResource::getEloquentQuery()
                    ->where('status_kontrak', 'Selesai')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Contract ID')
                    ->sortable(),
                TextColumn::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lokasi_proyek')
                    ->label('Lokasi Survey')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contract_types.type')
                    ->label('Tujuan Kontrak')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('industries.type')
                    ->label('Jenis Industri')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('durasi_kontrak')
                    ->color(function ($state): string {
                        if ($state > 14) {
                            return 'danger';
                        }
                        return 'success';
                    })
                    ->label('Waktu Penyelesaian Proyek')
                    ->sortable()
                    ->suffix(' hari'),
            ]);
    }
}
