<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Asset;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Industry;
use App\Models\Survey;
use App\Models\Surveyor;
use Filament\Actions\Action;
use Filament\Actions\ButtonAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\FormsComponent;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\DB;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as SectionInfoList;
use Filament\Tables\Filters\SelectFilter;
use App\Models\User;
use Filament\Forms\Get;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Exports\ContractExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationGroup = 'Project Management';

    protected static ?string $slug = 'orders';

    protected static ?string $recordTitleAttribute = 'pemberi_tugas';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $modelLabel = 'Order';

    public static function getFormSchema(): array
    {
        $calculate = function ($get, $set) {
            $startDate = $get('tanggal_kontrak');
            $endDate = $get('selesai_kontrak');
            $statusKontrak = $get('status_kontrak');

            if (!($startDate && $endDate)) {
                $set('durasi_kontrak', null);
                $set('status_kontrak', 'In Progress');
                $contract = Contract::find($get('id')); // Replace 'id' with the actual primary key field name
                // $contract->update(['durasi_kontrak' => null, 'is_available' => 1]); // Assuming is_available is 1 when status_kontrak is 'In Progress'
                return;
            }

            $startDate = \Carbon\Carbon::parse($startDate);
            $endDate = \Carbon\Carbon::parse($endDate);

            if (!$endDate) {
                return;
            }

            if ($startDate->gt($endDate)) {
                // If the start date is after the end date, this field is invalid
                $set('durasi_kontrak', null);
                $set('status_kontrak', 'In Progress');
                $contract = Contract::find($get('id')); // Replace 'id' with the actual primary key field name
                // $contract->update(['durasi_kontrak' => null, 'is_available' => 1]); // Assuming is_available is 1 when status_kontrak is 'In Progress'
                return;
            }

            $diffInDays = $startDate->diffInDays($endDate);

            $set('durasi_kontrak', $diffInDays);

            if ($statusKontrak === 'Selesai' || $statusKontrak === 'Batal') {
                $set('is_available', 0);
            }

            if ($startDate && $endDate) {
                $set('status_kontrak', 'Selesai');
            }

            // if ($startDate) {
            //     $set('status_kontrak', 'In Progress');
            // } 

            $contract = Contract::find($get('id')); // Replace 'id' with the actual primary key field name
            $contract->update(['durasi_kontrak' => $diffInDays, 'is_available' => $get('is_available')]);
        };

        return [
            Section::make('Survey Information')
                ->schema([
                    Forms\Components\Select::make('surveys_id')
                        ->label('ID Survey')
                        ->options(Survey::all()->pluck('id')->toArray()),
                    Forms\Components\Select::make('surveyors_id')
                        ->label('Surveyor')
                        ->options(Surveyor::all()->pluck('name', 'id')->toArray())
                ])
                ->columns(2)
                ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
            Section::make('Order Information')
                ->schema([
                    Forms\Components\TextInput::make('pemberi_tugas')
                        ->label('Pemberi Tugas')
                        ->required(),
                    Forms\Components\Select::make('industries_id')
                        ->options(Industry::all()->pluck('type', 'id')->toArray())
                        ->required()
                        ->label('Jenis Industri'),
                    Forms\Components\Select::make('contract_types_id')
                        ->options(ContractType::all()->pluck('type', 'id')->toArray())
                        ->required()
                        ->label('Tujuan Order'),
                    Forms\Components\TextInput::make('lokasi_proyek')
                        ->label('Lokasi Proyek')
                        ->required(),
                    Forms\Components\Select::make('assets_id')
                        ->options(Asset::all()->pluck('type', 'id')->toArray())
                        ->label('Jenis Aset')
                        ->required(),
                    Forms\Components\DatePicker::make('tanggal_kontrak')
                        ->label('Tanggal Order')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated($calculate)
                        ->afterOrEqual('today')
                        ->rules('bail', 'before_or_equal_if_filled:selesai_kontrak'),
                    Forms\Components\DatePicker::make('selesai_kontrak')
                        ->label('Selesai Order')
                        ->reactive()
                        ->afterStateUpdated($calculate)
                        ->afterOrEqual('tanggal_kontrak')
                        ->rules('bail', 'before_or_equal_if_filled:tanggal_kontrak')
                        ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
                    Forms\Components\Select::make('status_kontrak')
                        ->label('Status Order')
                        ->reactive()
                        ->options([
                            'Selesai' => 'Selesai',
                            'In Progress' => 'In Progress',
                            'Batal' => 'Batal',
                        ])
                        ->default('Pending')
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $selesaiKontrak = $get('selesai_kontrak');
                            $statusKontrak = $get('status_kontrak');

                            if ($statusKontrak === 'In Progress') {
                                $set('selesai_kontrak', null);
                                $set('durasi_kontrak', null);
                                $set('is_available', 1);
                                $contract = Contract::find($get('id')); // Replace 'id' with the actual primary key field name
                                $contract->update(['durasi_kontrak' => null, 'is_available' => 1]); // Assuming is_available is 1 when status_kontrak is 'In Progress'
                            } elseif ($statusKontrak === 'Batal') {
                                $set('selesai_kontrak', null);
                                $set('durasi_kontrak', null);
                                $set('is_available', 0);
                            } elseif ($statusKontrak === 'Selesai' && !$selesaiKontrak) {
                                $set('status_kontrak', 'In Progress');
                                $set('is_available', 1);
                                // You can also add a message here to inform the user why status_kontrak was not updated
                            } elseif ($statusKontrak === 'Selesai' || $statusKontrak === 'Batal') {
                                $set('is_available', 0);
                            }
                        })
                        ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
                    Forms\Components\TextInput::make('durasi_kontrak')
                        ->disabled()
                        ->label('Durasi Order')
                        ->suffix(' hari')
                        ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
                ])
                ->columns(2)
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->label('Order ID'),
            Tables\Columns\TextColumn::make('pemberi_tugas')
                ->searchable()
                ->label('Pemberi Tugas'),
            Tables\Columns\TextColumn::make('industries.type')
                ->searchable()
                ->sortable()
                ->label('Jenis Industri'),
            Tables\Columns\TextColumn::make('contract_types.type')
                ->searchable()
                ->sortable()
                ->label('Tujuan Order'),
            Tables\Columns\TextColumn::make('lokasi_proyek')
                ->searchable()
                ->sortable()
                ->label('Lokasi Proyek'),
            Tables\Columns\TextColumn::make('assets.type')
                ->searchable()
                ->label('Jenis Aset'),
            Tables\Columns\TextColumn::make('status_kontrak')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Selesai' => 'success',
                    'In Progress' => 'warning',
                    'Batal' => 'danger',
                    'Pending' => 'warning',
                    'Ditolak' => 'danger',
                })
                ->sortable()
                ->label('Status Order')
                // ->default('In Progress')
                ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
            Tables\Columns\TextColumn::make('tanggal_kontrak')
                ->sortable()
                ->label('Tanggal Order'),
            Tables\Columns\TextColumn::make('selesai_kontrak')
                ->sortable()
                ->label('Selesai Order')
                ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
            Tables\Columns\TextColumn::make('durasi_kontrak')
                ->sortable()
                ->suffix(' hari')
                ->label('Durasi Order')
                ->visible(fn (Get $get): bool => auth()->user()->role === 'admin'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                SelectFilter::make('status_kontrak')
                    ->multiple()
                    ->label('Status Order')
                    ->options([
                        'Batal' => 'Batal',
                        'In Progress' => 'In Progress',
                        'Selesai' => 'Selesai',
                        'Pending' => 'Pending'
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(ContractExporter::class)
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->emptyStateHeading('No orders yet');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionInfoList::make('Order Information')
                    ->schema([
                        TextEntry::make('id')
                            ->label('Contract ID'),
                        TextEntry::make('pemberi_tugas')
                            ->label('Pemberi Tugas'),
                        TextEntry::make('industries.type')
                            ->label('Jenis Industri'),
                        TextEntry::make('contract_types.type')
                            ->label('Tujuan Kontrak'),
                        TextEntry::make('lokasi_proyek')
                            ->label('Lokasi Proyek'),
                        TextEntry::make('assets.type')
                            ->label('Jenis Aset'),
                    ])
                    ->columns(2),
                SectionInfoList::make('Survey Information')
                    ->schema([
                        // TextEntry::make('surveys_id')
                        //     ->label('ID Survey'),
                        TextEntry::make('surveyors.name')
                            ->label('Surveyor'),
                        TextEntry::make('surveys.pemilik_aset')
                            ->label('Pemilik Aset'),
                        TextEntry::make('surveys.tanggal_survey')
                            ->label('Tanggal Survey'),
                        TextEntry::make('assets.type')
                            ->label('Jenis Aset'),
                        TextEntry::make('surveys.keterangan_aset')
                            ->label('Keterangan Aset'),
                        ImageEntry::make('surveys.gambar_aset')
                            ->label('Gambar Aset'),
                        TextEntry::make('surveys.harga_aset')
                            ->label('Harga Aset')
                            ->prefix('Rp. ')
                            ->numeric(
                                decimalPlaces: 0,
                                decimalSeparator: '.',
                                thousandsSeparator: '.',
                            )
                    ])
                    ->columns(2),
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
            'view' => Pages\ViewContract::route('/{record}'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->role == 'admin' || auth()->user()->role == 'debitur';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role == 'debitur';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role == 'admin';
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()->role == 'admin' || auth()->user()->role == 'surveyor';
    // }

    public static function getGloballySearchableAttributes(): array
    {
        return ['pemberi_tugas', 'industries.type', 'contract_types.type', 'lokasi_proyek', 'tanggal_kontrak', 'selesai_kontrak'];
    }
}
