<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyorResource\Pages;
use App\Filament\Resources\SurveyorResource\RelationManagers;
use App\Models\Surveyor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyorResource extends Resource
{
    protected static ?string $model = Surveyor::class;

    protected static ?string $navigationGroup = 'Staff Management';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama'),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Email'),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->label('Nomor Telepon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Surveyor'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon'),
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
            'index' => Pages\ListSurveyors::route('/'),
            'create' => Pages\CreateSurveyor::route('/create'),
            'edit' => Pages\EditSurveyor::route('/{record}/edit'),
        ];
    }
}
