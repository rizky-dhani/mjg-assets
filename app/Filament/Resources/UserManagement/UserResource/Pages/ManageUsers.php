<?php

namespace App\Filament\Resources\UserManagement\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserManagement\UserResource;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New User'),
        ];
    }
}
