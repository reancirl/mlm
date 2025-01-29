<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Actions;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $record = parent::handleRecordCreation($data);

        // Assign the "Member" role to the newly created user
        $record->assignRole('Member');

        return $record;
    }
}
