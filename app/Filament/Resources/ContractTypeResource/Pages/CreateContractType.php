<?php

namespace App\Filament\Resources\ContractTypeResource\Pages;

use App\Filament\Resources\ContractTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContractType extends CreateRecord
{
    protected static string $resource = ContractTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
