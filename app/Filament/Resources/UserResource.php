<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Setup';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles')->multiple()->relationship('roles', 'name'),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\BelongsToSelect::make('instance_id')
                    ->relationship('instance', 'name')
                    ->label('Instance')
                    ->required(fn() => !auth()->user()->hasRole('Super Admin')) // Required for non-Super Admins
                    ->visible(fn() => auth()->user()->hasRole('Super Admin')),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('instance.name')
                    ->label('Instance')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()->hasRole('Super Admin')), // Only visible to Super Admin

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable()
                    ->searchable(),
                    // ->formatStateUsing(fn($state) => $state ? implode(', ', $state) : 'None')

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
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
                SelectFilter::make('instance_id')
                    ->relationship('instance', 'name')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
