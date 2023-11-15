<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use App\Models\Surveyor;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $slug = 'surveys';

    protected static ?string $navigationGroup = 'Project Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('surveyors_id')
                    ->options(Surveyor::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->label('Surveyor'),
                Forms\Components\Select::make('contracts_id')
                    ->options(Contract::all()->pluck('pemberi_tugas', 'id')->toArray())
                    ->required()
                    ->label('Pemberi Tugas'),
                Forms\Components\TextInput::make('pemilik_aset')
                    ->label('Pemilik Aset')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_survey')
                    ->label('Tanggal Survey')
                    ->required(),
                Forms\Components\TextInput::make('jenis_aset')
                    ->label('Jenis Aset')
                    ->required(),
                Forms\Components\TextInput::make('keterangan_aset')
                    ->label('Keterangan Aset')
                    ->required(),
                Forms\Components\TextInput::make('harga_aset')
                    ->label('Harga Aset')
                    ->prefix('Rp.')
                    ->required(),
                Forms\Components\FileUpload::make('gambar_aset')
                    ->label('Gambar Aset')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('Survey ID'),
                Tables\Columns\TextColumn::make('surveyors.name')
                    ->sortable()
                    ->label('Surveyor'),
                Tables\Columns\TextColumn::make('pemilik_aset')
                    ->searchable()
                    ->label('Pemilik Aset'),
                Tables\Columns\TextColumn::make('tanggal_survey')
                    ->searchable()
                    ->sortable()
                    ->label('Tanggal Survey'),
                Tables\Columns\TextColumn::make('jenis_aset')
                    ->searchable()
                    ->label('Jenis Aset'),
                Tables\Columns\TextColumn::make('keterangan_aset')
                    ->searchable()
                    ->label('Keterangan Aset'),
                Tables\Columns\ImageColumn::make('gambar_aset')
                    ->label('Gambar Aset')
                    ->square(),
                Tables\Columns\TextColumn::make('harga_aset')
                    ->searchable()
                    ->prefix('Rp. ')
                    ->numeric(
                        decimalPlaces:0,
                        decimalSeparator:'.',
                        thousandsSeparator:'.',
                    )
                    ->label('Harga Aset'),
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
            'index' => Pages\ListSurvey::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
