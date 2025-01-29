<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationGroup = 'Staffing';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'id') // Use 'id' as the column for the relationship
                    ->getSearchResultsUsing(function (string $query) {
                        $user = auth()->user(); // Get the currently logged-in user
            
                        return \App\Models\Employee::whereHas('user', function ($queryBuilder) use ($query, $user) {
                            $queryBuilder
                                ->where('first_name', 'LIKE', "%{$query}%")
                                ->orWhere('middle_name', 'LIKE', "%{$query}%")
                                ->orWhere('last_name', 'LIKE', "%{$query}%");
                        })
                            ->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
                                $query->where('instance_id', $user->instance_id); // Filter by instance_id for non-Super Admins
                            })
                            ->get()
                            ->mapWithKeys(function ($employee) {
                                return [$employee->id => "{$employee->user->first_name} {$employee->user->middle_name} {$employee->user->last_name}"];
                            });
                    })
                    ->getOptionLabelUsing(fn($value) => \App\Models\Employee::find($value)?->user->name) // Use the computed `name` attribute
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->default(now()->toDateString()) // Default to current day
                    ->required(),
                Forms\Components\TimePicker::make('time_in')
                    ->nullable(),
                Forms\Components\TimePicker::make('time_out')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'leave' => 'Leave',
                        'half-day' => 'Half-Day',
                    ])
                    ->default('present')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable()
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('time_in')
                    ->label('Time In'),
                Tables\Columns\TextColumn::make('time_out')
                    ->label('Time Out'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'leave' => 'Leave',
                        'half-day' => 'Half-Day',
                        default => 'Unknown',
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user && !$user->hasRole('Super Admin')) {
                    // Filter employees by the instance_id in the related user
                    $query->whereHas('employee', function ($query) use ($user) {
                        $query->where('instance_id', $user->instance_id);
                    });
                }
                return $query;
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'leave' => 'Leave',
                        'half-day' => 'Half-Day',
                    ]),
                Tables\Filters\Filter::make('date')
                    ->label('Date')
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->default(now()->toDateString())
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['date'])) {
                            $query->where('date', $data['date']);
                        }
                    })
                    ->default([
                        'date' => now()->toDateString(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
