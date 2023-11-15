<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationGroup = 'Project Management';

    protected static ?string $slug = 'contracts';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {

        $calculate = function ($get, $set) {
            $startDate = $get('tanggal_kontrak');
            $endDate = $get('selesai_kontrak');

            if (!($startDate && $endDate)) {
                return;
            }

            $startDate = \Carbon\Carbon::parse($startDate);
            $endDate = \Carbon\Carbon::parse($endDate);

            $diffInDays = $startDate->diffInDays($endDate);

            $set('durasi_kontrak', $diffInDays);
        };

        return $form
            ->schema([
                Forms\Components\TextInput::make('pemberi_tugas')
                    ->label('Pemberi Tugas')
                    ->required(),
                Forms\Components\TextInput::make('jenis_industri')
                    ->label('Jenis Industri')
                    ->required(),
                Forms\Components\TextInput::make('tujuan_kontrak')
                    ->label('Tujuan Kontrak')
                    ->required(),
                Forms\Components\TextInput::make('lokasi_proyek')
                    ->label('Lokasi Proyek')
                    ->required(),
                Forms\Components\DatePicker::make('selesai_kontrak')
                    ->label('Selesai Kontrak')
                    ->reactive()
                    ->afterStateUpdated($calculate)
                    ->afterOrEqual('tanggal_kontrak'),
                Forms\Components\DatePicker::make('tanggal_kontrak')
                    ->label('Tanggal Kontrak')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated($calculate)
                    ->beforeOrEqual('selesai_kontrak'),
                Forms\Components\Select::make('status_kontrak')
                    ->label('Status Kontrak')
                    ->options([
                        'Selesai' => 'Selesai',
                        'In Progress' => 'In Progress',
                    ]),
                Forms\Components\TextInput::make('durasi_kontrak')
                    ->disabled()
                    ->label('Durasi Kontrak')
                    ->suffix(' hari'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('Contract ID'),
                Tables\Columns\TextColumn::make('pemberi_tugas')
                    ->searchable()
                    ->label('Pemberi Tugas'),
                Tables\Columns\TextColumn::make('jenis_industri')
                    ->searchable()
                    ->sortable()
                    ->label('Jenis Industri'),
                Tables\Columns\TextColumn::make('tujuan_kontrak')
                    ->searchable()
                    ->sortable()
                    ->label('Tujuan Kontrak'),
                Tables\Columns\TextColumn::make('lokasi_proyek')
                    ->searchable()
                    ->sortable()
                    ->label('Lokasi Proyek'),
                Tables\Columns\TextColumn::make('status_kontrak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'In Progress' => 'warning',
                    })
                    ->sortable()
                    ->label('Status Kontrak')
                    ->default('In Progress'),
                Tables\Columns\TextColumn::make('tanggal_kontrak')
                    ->sortable()
                    ->label('Tanggal Kontrak'),
                Tables\Columns\TextColumn::make('selesai_kontrak')
                    ->sortable()
                    ->label('Selesai Kontrak'),
                Tables\Columns\TextColumn::make('durasi_kontrak')
                    ->sortable()
                    ->suffix(' hari')
                    ->label('Durasi Kontrak'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
