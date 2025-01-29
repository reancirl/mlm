<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function handleRecordCreation(array $data): Employee
    {
        $currentUser = Auth::user();

        // Determine the instance_id
        if (!$currentUser->hasRole('Super Admin')) {
            $data['instance_id'] = $currentUser->instance_id;
        }

        // Create the associated user
        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'], // Already hashed in the form
            'instance_id' => $data['instance_id'],
        ]);

        // Assign the "Staff" role to the user
        $user->assignRole('Staff');

        // Create the employee record and associate it with the user
        return Employee::create([
            'user_id' => $user->id,
            'instance_id' => $user->instance_id,
            'position' => $data['position'],
            'employment_type' => $data['employment_type'],
            'start_date' => $data['start_date'],
            'salary' => $data['salary'],
            'sss_number' => $data['sss_number'],
            'pagibig_number' => $data['pagibig_number'],
            'philhealth_number' => $data['philhealth_number'],
        ]);
    }
}
