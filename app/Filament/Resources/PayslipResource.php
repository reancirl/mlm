<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayslipResource\Pages;
use App\Models\Payslip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PayslipResource extends Resource
{
    protected static ?string $model = Payslip::class;

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
                Forms\Components\DatePicker::make('period_start')
                    ->required()
                    ->label('Period Start'),
                Forms\Components\DatePicker::make('period_end')
                    ->required()
                    ->label('Period End'),
                Forms\Components\TextInput::make('basic_salary')
                    ->numeric()
                    ->required()
                    ->label('Basic Salary'),
                Forms\Components\TextInput::make('deductions')
                    ->numeric()
                    ->nullable()
                    ->label('Deductions'),
                Forms\Components\TextInput::make('sss_contribution')
                    ->numeric()
                    ->nullable()
                    ->label('SSS Contribution'),
                Forms\Components\TextInput::make('pagibig_contribution')
                    ->numeric()
                    ->nullable()
                    ->label('Pag-IBIG Contribution'),
                Forms\Components\TextInput::make('philhealth_contribution')
                    ->numeric()
                    ->nullable()
                    ->label('PhilHealth Contribution'),
                Forms\Components\TextInput::make('overtime')
                    ->numeric()
                    ->nullable()
                    ->label('Overtime'),
                Forms\Components\TextInput::make('net_pay')
                    ->numeric()
                    ->required()
                    ->label('Net Pay'),
                Forms\Components\DateTimePicker::make('generated_at')
                    ->default(now())
                    ->disabled()
                    ->label('Generated At'),
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
                Tables\Columns\TextColumn::make('period_start')
                    ->label('Period Start')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_end')
                    ->label('Period End')
                    ->sortable(),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deductions')
                    ->label('Deductions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_pay')
                    ->label('Net Pay')
                    ->sortable(),
                Tables\Columns\TextColumn::make('generated_at')
                    ->label('Generated At')
                    ->sortable(),
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
                Tables\Filters\Filter::make('period_start')
                    ->label('Period Start')
                    ->form([
                        Forms\Components\DatePicker::make('period_start')->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['period_start'])) {
                            $query->where('period_start', $data['period_start']);
                        }
                    }),
                Tables\Filters\Filter::make('period_end')
                    ->label('Period End')
                    ->form([
                        Forms\Components\DatePicker::make('period_end')->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['period_end'])) {
                            $query->where('period_end', $data['period_end']);
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
            'index' => Pages\ListPayslips::route('/'),
            'create' => Pages\CreatePayslip::route('/create'),
            'edit' => Pages\EditPayslip::route('/{record}/edit'),
        ];
    }
}
