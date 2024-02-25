<?php

namespace App\Filament\Exports;

use App\Models\Contract;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;


class ContractExporter extends Exporter
{
    protected static ?string $model = Contract::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('surveyors.name'),
            ExportColumn::make('surveys_id'),
            ExportColumn::make('pemberi_tugas'),
            ExportColumn::make('industries.id'),
            ExportColumn::make('contract_types.id'),
            ExportColumn::make('assets.id'),
            ExportColumn::make('lokasi_proyek'),
            ExportColumn::make('tanggal_kontrak'),
            ExportColumn::make('selesai_kontrak'),
            ExportColumn::make('status_kontrak'),
            ExportColumn::make('durasi_kontrak'),
            ExportColumn::make('is_available'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return "orders-summary-{$export->getKey()}.csv";
    }
}
