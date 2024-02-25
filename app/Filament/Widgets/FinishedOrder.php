<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use App\Models\Contract;
use App\Models\Surveyor;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;

class FinishedOrder extends BaseWidget
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
                    ->label('Order ID')
                    ->sortable(),
                TextColumn::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('surveyors.name')
                    ->label('Surveyor')
                    ->searchable(),
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
                TextColumn::make('assets.type')
                    ->label('Jenis Aset')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_kontrak')
                    ->label('Tanggal Order')
                    ->sortable(),
                TextColumn::make('selesai_kontrak')
                    ->label('Selesai Order')
                    ->sortable(),
                BadgeColumn::make('durasi_kontrak')
                    ->color(function ($state): string {
                        if ($state > 14) {
                            return 'danger';
                        }
                        return 'success';
                    })
                    ->label('Durasi Order')
                    ->sortable()
                    ->suffix(' hari'),
            ])
            ->filters([
                SelectFilter::make('surveyors_id')
                    ->label('Surveyor')
                    ->options([
                        Surveyor::all()->pluck('name', 'id')->toArray()
                    ])
                    ->multiple(),
            ])
            ->emptyStateHeading('No orders yet');
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
