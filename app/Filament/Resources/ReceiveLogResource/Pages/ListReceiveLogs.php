<?php

namespace App\Filament\Resources\ReceiveLogResource\Pages;

use App\Filament\Resources\ReceiveLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiveLogs extends ListRecords
{
    protected static string $resource = ReceiveLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
