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
            ExportColumn::make('surveyors.name')
                ->label('Surveyor'),
            ExportColumn::make('surveys_id')
                ->label('ID Survey'),
            ExportColumn::make('pemberi_tugas')
                ->label('Debitur'),
            ExportColumn::make('industries.type')
                ->label('Jenis Industri'),
            ExportColumn::make('contract_types.type')
                ->label('Tujuan Order'),
            ExportColumn::make('assets.type')
                ->label('Jenis Aset'),
            ExportColumn::make('surveys.keterangan_aset')
                ->label('Keterangan Aset'),
            ExportColumn::make('lokasi_proyek')
                ->label('Lokasi Survey'),
            ExportColumn::make('tanggal_kontrak')
                ->label('Tanggal Order'),
            ExportColumn::make('selesai_kontrak')
                ->label('Selesai Order'),
            ExportColumn::make('status_kontrak')
                ->label('Status Order'),
            ExportColumn::make('durasi_kontrak')
                ->label('Durasi Penyelesaian Order'),
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
        return "orders-summary-{$export->getKey()}";
    }
}
