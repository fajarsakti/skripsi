<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class OngoingOrder extends BaseWidget
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
                    ->where('status_kontrak', 'In Progress')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
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
                    ->label('Tujuan Order')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('industries.type')
                    ->label('Jenis Industri')
                    ->searchable()
                    ->sortable(),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'admin';
    }
}
