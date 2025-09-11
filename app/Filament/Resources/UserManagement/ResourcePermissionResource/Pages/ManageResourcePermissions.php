<?php

namespace App\Filament\Resources\UserManagement\ResourcePermissionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\UserManagement\ResourcePermissionResource;

class ManageResourcePermissions extends ManageRecords
{
    protected static string $resource = ResourcePermissionResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data){
                    $data['permissionId'] = Str::orderedUuid();
                    return $data;
                }),
        ];
    }
}

