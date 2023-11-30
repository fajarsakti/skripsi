<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\ContractResource;
use App\Filament\Resources\SurveyorResource;
use Filament\Tables\Actions\Action;
use App\Models\Survey;
use App\Models\Contract;
use App\Models\Surveyor;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use FontLib\Table\Type\post;
use App\Filament\Resources\SurveyResource;
use Filament\Notifications\Notification;

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
                TextColumn::make('contract_types.type')
                    ->label('Tujuan Kontrak')
                    ->sortable(),
                TextColumn::make('industries.type')
                    ->label('Jenis Industri')
                    ->sortable(),
                IconColumn::make('is_available')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Availability'),
            ])
            ->actions([
                Action::make('survey_contract')
                    ->button()
                    ->disabled(function (Contract $record) {
                        return $record->is_available === 0;
                    })
                    ->label('Lakukan Survey')
                    ->action(function () {
                        return redirect('/admin/surveys/create');
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Lakukan survey untuk kontrak ini?')
                    ->modalSubmitActionLabel('Ya, lakukan survey')
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->role == 'surveyor';
    }
}
