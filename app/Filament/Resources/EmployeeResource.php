<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'Staffing';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Personal Details')
                    ->label('User Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name')
                            ->placeholder('Enter first name')
                            ->required()
                            ->maxLength(255)
                            ->default(fn($record) => $record->user->first_name ?? ''), // Default value from user relationship
                        TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->placeholder('Enter middle name')
                            ->maxLength(255)
                            ->default(fn($record) => $record->user->middle_name ?? ''),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->placeholder('Enter last name')
                            ->required()
                            ->maxLength(255)
                            ->default(fn($record) => $record->user->last_name ?? ''),
                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('Enter email address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->default(fn($record) => $record->user->email ?? ''),
                        BelongsToSelect::make('instance_id')
                            ->relationship('instance', 'name')
                            ->label('Instance')
                            ->required(fn() => !auth()->user()->hasRole('Super Admin'))
                            ->visible(fn() => auth()->user()->hasRole('Super Admin')),
                        TextInput::make('password')
                            ->label('Password')
                            ->placeholder('Enter a secure password')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state) => $state ? Hash::make($state) : null)
                            ->maxLength(255),
                    ]),
                Fieldset::make('Employment Details')
                    ->schema([
                        TextInput::make('position')
                            ->label('Position')
                            ->placeholder('Enter position/title'),
                        Select::make('employment_type')
                            ->label('Employment Type')
                            ->options([
                                'full-time' => 'Full-Time',
                                'part-time' => 'Part-Time',
                                'contract' => 'Contract',
                            ])
                            ->placeholder('Select employment type')
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->placeholder('Select start date'),
                        TextInput::make('salary')
                            ->label('Salary')
                            ->numeric()
                            ->placeholder('Enter salary (optional)')
                            ->nullable(),
                    ]),
                Fieldset::make('Government Info')
                    ->schema([
                        TextInput::make('sss_number')
                            ->label('SSS Number')
                            ->placeholder('Enter SSS number')
                            ->nullable(),
                        TextInput::make('pagibig_number')
                            ->label('Pag-Ibig Number')
                            ->placeholder('Enter Pag-Ibig number')
                            ->nullable(),
                        TextInput::make('philhealth_number')
                            ->label('PhilHealth Number')
                            ->placeholder('Enter PhilHealth number')
                            ->nullable(),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Position')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employment_type')
                    ->label('Employment Type')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.instance.name')
                    ->label('Instance') // Fetch instance name via user relationship
                    ->sortable()
                    ->visible(fn() => auth()->user()->hasRole('Super Admin')), // Only visible to Super Admin
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user && !$user->hasRole('Super Admin')) {
                    $query->where('instance_id', $user->instance_id);
                }
                return $query;
            })
            ->filters([
                SelectFilter::make('user.instance_id') // Filter based on instance_id via user
                    ->relationship('user.instance', 'name') // Filter through the user->instance relationship
                    ->label('Instance')
                    ->visible(fn() => auth()->user()->hasRole('Super Admin')), // Only visible to Super Admin
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
