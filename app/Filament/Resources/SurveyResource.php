<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Asset;
use App\Models\Survey;
use App\Models\Surveyor;
use App\Models\Contract;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Closure;
use Filament\Forms\Components\Section;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Filament\Resources\SurveyResource\Pages\ViewSurvey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Filament\Infolists\Components\Section as SectionInfoList;
use Filament\Tables\Filters\SelectFilter;


class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $slug = 'surveys';

    protected static ?string $recordTitleAttribute = 'pemilik_aset';

    protected static ?string $navigationGroup = 'Project Management';

    public static function getInProgressContractsOptions()
    {
        // Retrieve the contracts with 'In Progress' status
        $contracts = Contract::where('status_kontrak', 'In Progress')->get();

        // Map the contracts to a format suitable for options
        $contractOptions = $contracts->pluck('pemberi_tugas', 'id')->toArray();

        return $contractOptions;
    }

    public static function getContractAsset()
    {
        $assets_id = Contract::pluck('assets_id')->toArray();
        $assets = Asset::whereIn('id', $assets_id)->get();

        $getAsset = $assets->pluck('type', 'id')->toArray();

        return $getAsset;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Information')
                    ->schema([
                        Forms\Components\Select::make('contract_id')
                            ->options(static::getInProgressContractsOptions()) // Use the custom method here
                            ->required()
                            ->label('Pemberi Tugas'),
                        Forms\Components\Select::make('assets_id')
                            ->options(static::getContractAsset())
                            ->required()
                            ->label('Jenis Aset'),
                    ])
                    ->columns(2),
                Section::make('Survey Fulfillment ')
                    ->schema([
                        Forms\Components\Select::make('surveyors_id')
                            ->options(Surveyor::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->label('Surveyor'),
                        Forms\Components\Select::make('assignments_id')
                            ->options(Assignment::all()->pluck('no_penugasan', 'id')->toArray())
                            ->required()
                            ->prefix('KJPP')
                            ->label('Nomor Penugasan'),
                        Forms\Components\TextInput::make('pemilik_aset')
                            ->label('Pemilik Aset')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_survey')
                            ->label('Tanggal Survey')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan_aset')
                            ->label('Keterangan Aset')
                            ->required(),
                        Forms\Components\TextInput::make('harga_aset')
                            ->label('Harga Aset')
                            ->prefix('Rp.')
                            ->numeric()
                            ->required(),
                        Forms\Components\FileUpload::make('gambar_aset')
                            ->label('Gambar Aset')
                            ->image(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('Survey ID'),
                Tables\Columns\TextColumn::make('assignments.no_penugasan')
                    ->sortable()
                    ->searchable()
                    ->prefix('KJPP')
                    ->label('Nomor Penugasan'),
                Tables\Columns\TextColumn::make('surveyors.name')
                    ->sortable()
                    ->searchable()
                    ->label('Surveyor'),
                Tables\Columns\TextColumn::make('pemilik_aset')
                    ->searchable()
                    ->label('Pemilik Aset'),
                Tables\Columns\TextColumn::make('tanggal_survey')
                    ->searchable()
                    ->sortable()
                    ->label('Tanggal Survey'),
                Tables\Columns\TextColumn::make('assets.type')
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
                    ->sortable()
                    ->prefix('Rp. ')
                    ->suffix(',00')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: '.',
                    )
                    ->label('Harga Aset'),
            ])
            ->filters([
                SelectFilter::make('surveyors_id')
                    ->multiple()
                    ->label('Surveyor')
                    ->options(
                        function () {
                            $data = Surveyor::all();

                            return $data->pluck('name', 'id')->toArray();
                        }
                    )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionInfolist::make('Survey Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Survey ID'),
                        Infolists\Components\TextEntry::make('surveyors.name')
                            ->label('Surveyor'),
                        Infolists\Components\TextEntry::make('pemilik_aset')
                            ->label('Pemilik Aset'),
                        Infolists\Components\TextEntry::make('tanggal_survey')
                            ->label('Tanggal Survey'),
                        Infolists\Components\TextEntry::make('assets.type')
                            ->label('Jenis Aset'),
                        Infolists\Components\TextEntry::make('keterangan_aset')
                            ->label('Keterangan Aset'),
                        Infolists\Components\ImageEntry::make('gambar_aset')
                            ->label('Gambar Aset'),
                        Infolists\Components\TextEntry::make('harga_aset')
                            ->label('Harga Aset')
                            ->prefix('Rp. ')
                            ->numeric(
                                decimalPlaces: 0,
                                decimalSeparator: '.',
                                thousandsSeparator: '.',
                            )
                    ])
                    ->columns(2)
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
            'view' => Pages\ViewSurvey::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role == 'surveyor' || auth()->user()->role == 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role == 'surveyor';
    }

    // public static function canEdit(Model $record): bool
    // {
    //     return auth()->user()->role == 'surveyor';
    // }

    public static function getGloballySearchableAttributes(): array
    {
        return ['pemilik_aset', 'surveyors.name', 'assets.type', 'keterangan_aset', 'harga_aset'];
    }
}
