<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationGroup = 'Staffing';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'id')
                    ->getSearchResultsUsing(function (string $query) {
                        $user = auth()->user();

                        return \App\Models\Employee::whereHas('user', function ($queryBuilder) use ($query) {
                            $queryBuilder
                                ->where('first_name', 'LIKE', "%{$query}%")
                                ->orWhere('middle_name', 'LIKE', "%{$query}%")
                                ->orWhere('last_name', 'LIKE', "%{$query}%");
                        })
                            ->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
                                $query->where('instance_id', $user->instance_id);
                            })
                            ->get()
                            ->mapWithKeys(function ($employee) {
                                return [$employee->id => "{$employee->user->first_name} {$employee->user->middle_name} {$employee->user->last_name}"];
                            });
                    })
                    ->getOptionLabelUsing(fn($value) => \App\Models\Employee::find($value)?->user->name)
                    ->searchable()
                    ->required()
                    ->label('Employee'),
                Forms\Components\Select::make('leave_type')
                    ->options([
                        'vacation' => 'Vacation',
                        'sick' => 'Sick',
                        'emergency' => 'Emergency',
                        'others' => 'Others',
                    ])
                    ->required()
                    ->label('Leave Type'),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Start Date'),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('End Date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required()
                    ->label('Status'),
                Forms\Components\Textarea::make('reason')
                    ->nullable()
                    ->label('Reason'),
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
                Tables\Columns\TextColumn::make('leave_type')
                    ->label('Leave Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => 'Unknown',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Reason')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->reason),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user && !$user->hasRole('Super Admin')) {
                    $query->whereHas('employee', function ($query) use ($user) {
                        $query->where('instance_id', $user->instance_id);
                    });
                }
                return $query;
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('start_date')
                    ->label('Start Date')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['start_date'])) {
                            $query->where('start_date', $data['start_date']);
                        }
                    }),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
