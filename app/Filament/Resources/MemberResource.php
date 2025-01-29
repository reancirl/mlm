<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Policies\MemberPolicy;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $policy = MemberPolicy::class;
    protected static ?string $navigationLabel = 'Members';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // First Name
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),

            // Last Name
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),

            // Email
            Forms\Components\TextInput::make('email')
                ->email()
                ->unique(ignorable: fn($record) => $record)
                ->required(),

            // Password (only required on creation)
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn($record) => $record === null) // Only required on create
                ->dehydrateStateUsing(fn($state) => bcrypt($state))
                ->visibleOn('create'),

            // Upline (Parent Selection)
            Forms\Components\Select::make('parent_id')
                ->label('Upline (Parent)')
                ->options(function () {
                    $user = auth()->user();

                    if ($user->hasRole('Member')) {
                        // Members can only assign their downline under themselves
                        return User::where('id', $user->id)->pluck('first_name', 'id');
                    }

                    if ($user->hasRole(['Super Admin', 'Admin'])) {
                        // Admins and Super Admins can assign any Member in the same instance
                        return User::role('Member')
                            ->where('instance_id', $user->instance_id)
                            ->pluck('first_name', 'id');
                    }

                    return []; // Default: No options if no valid role
                })
                ->searchable()
                ->default(auth()->id()) // Set the default parent to the current user's ID
                ->visible(fn() => auth()->user()->hasRole(['Super Admin', 'Admin', 'Member'])),

                Forms\Components\Hidden::make('instance_id')
                ->default(fn() => auth()->user()->instance_id), // Automatically set to current user's instance_id
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('First Name'),
                Tables\Columns\TextColumn::make('last_name')->label('Last Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('parent.first_name')
                    ->label('Upline')
                    ->default('—'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user && !$user->hasRole('Super Admin')) {
                    if ($user->hasRole('Member')) {
                        // Filter for members in the same instance and only show their downline
                        $query->where('instance_id', $user->instance_id)
                            ->where('parent_id', $user->id)
                            ->whereHas('roles', function ($roleQuery) {
                            $roleQuery->where('name', 'Member');
                        });
                    } else {
                        // Filter for all members in the same instance for Admins
                        $query->where('instance_id', $user->instance_id)
                            ->whereHas('roles', function ($roleQuery) {
                            $roleQuery->where('name', 'Member');
                        });
                    }
                } else {
                    // For Super Admins, still filter to only show Member roles
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'Member');
                    });
                }
                return $query;
            })
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Member'; // singular
    }

    public static function getPluralModelLabel(): string
    {
        return 'Members'; // plural
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
