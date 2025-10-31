<?php

namespace App\Filament\Resources\UserManagement\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserManagement\UserResource;
use Filament\Resources\Pages\ManageRecords;
use Hash;
use Str;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New User')
            ->mutateFormDataUsing(function(array $data){
                $data['userId'] = Str::orderedUuid();
                $data['password'] = Hash::make('Medquest.1');
                return $data;
            })
            ->successNotificationTitle('User created successfully!'),
        ];
    }
}
