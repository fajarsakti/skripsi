<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssignmentResource\Pages;
use App\Filament\Resources\AssignmentResource\Pages\ViewAssignment;
use App\Filament\Resources\AssignmentResource\RelationManagers;
use App\Models\Assignment;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Surveyor;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists;
use Filament\Infolists\Components\Section as SectionInfoList;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static ?string $navigationGroup = 'Project Management';

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    public static function getInProgressContractsOptions()
    {
        // Retrieve the contracts with 'In Progress' status
        $contracts = Contract::where('status_kontrak', 'In Progress')->get();

        // Map the contracts to a format suitable for options
        $contractOptions = $contracts->pluck('pemberi_tugas', 'id')->toArray();

        return $contractOptions;
    }

    public static function getSurveyLocation()
    {
        $contracts = Contract::where('status_kontrak', 'In Progress')->get();

        $surveyLocation = $contracts->pluck('lokasi_proyek', 'id')->toArray();

        return $surveyLocation;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('surveyors_id')
                    ->label('Surveyor')
                    ->options(Surveyor::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\Select::make('contracts_id')
                    ->label('Debitur')
                    ->options(static::getInProgressContractsOptions())
                    ->required(),
                Forms\Components\Select::make('contracts_id')
                    ->label('Lokasi Survey')
                    ->required()
                    ->options(static::getSurveyLocation()),
                Forms\Components\TextInput::make('no_penugasan')
                    ->label('Nomor Penugasan')
                    ->required()
                    ->prefix('KJPP'),
                Forms\Components\DatePicker::make('tanggal_penugasan')
                    ->label('Tanggal Penugasan')
                    ->default('created_at')
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionInfoList::make('Assignment Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Assignment ID'),
                        Infolists\Components\TextEntry::make('surveyors.name')
                            ->label('Surveyor'),
                        Infolists\Components\TextEntry::make('contracts.pemberi_tugas')
                            ->label('Debitur'),
                        Infolists\Components\TextEntry::make('contracts.assets.type')
                            ->label('Jenis Aset'),
                        Infolists\Components\TextEntry::make('no_penugasan')
                            ->label('Nomor Penugasan')
                            ->prefix('KJPP'),
                        Infolists\Components\TextEntry::make('tanggal_penugasan')
                            ->label('Tanggal Penugasan'),
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
                    ->label('Assignment ID'),
                Tables\Columns\TextColumn::make('surveyors.name')
                    ->searchable()
                    ->label('Surveyor'),
                Tables\Columns\TextColumn::make('contracts.pemberi_tugas')
                    ->searchable()
                    ->label('Debitur'),
                Tables\Columns\TextColumn::make('contracts.assets.type')
                    ->searchable()
                    ->label('Jenis Aset'),
                Tables\Columns\TextColumn::make('no_penugasan')
                    ->searchable()
                    ->label('Nomor Penugasan')
                    ->prefix('KJPP'),
                Tables\Columns\TextColumn::make('tanggal_penugasan')
                    ->searchable()
                    ->label('Tanggal Penugasan')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssignments::route('/'),
            'create' => Pages\CreateAssignment::route('/create'),
            'edit' => Pages\EditAssignment::route('/{record}/edit'),
            'view' => Pages\ViewAssignment::route('/{record}')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role == 'surveyor' || auth()->user()->role == 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->role == 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role == 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role == 'admin';
    }
}
