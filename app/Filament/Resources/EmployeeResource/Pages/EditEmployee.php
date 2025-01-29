<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record && $this->record->user) {
            $data['first_name'] = $this->record->user->first_name ?? '';
            $data['middle_name'] = $this->record->user->middle_name ?? '';
            $data['last_name'] = $this->record->user->last_name ?? '';
            $data['email'] = $this->record->user->email ?? '';
            $data['instance_id'] = $this->record->user->instance_id ?? null; // Safely get instance_id
        } else {
            $data['first_name'] = '';
            $data['middle_name'] = '';
            $data['last_name'] = '';
            $data['email'] = '';
            $data['instance_id'] = auth()->user()->instance_id ?? null; // Default to the current user's instance_id
        }
    
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->user) {
            $this->record->user->update([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? $this->record->user->password,
            ]);
        }

        unset($data['first_name'], $data['middle_name'], $data['last_name'], $data['email'], $data['password']);

        return $data;
    }
}
