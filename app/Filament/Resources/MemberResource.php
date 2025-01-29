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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Members';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->unique(ignorable: fn ($record) => $record)
                ->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($record) => $record === null) // Only required on create
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->visibleOn('create'),

            // Parent selection (only for Admin)
            Forms\Components\Select::make('parent_id')
                ->label('Upline (Parent)')
                ->options(function () {
                    // For example, only show existing Members as possible parents
                    return User::role('Member')->pluck('first_name', 'id');
                })
                ->searchable()
                ->visible(fn () => auth()->user()->hasRole(['Super Admin','Admin'])),
                // If the user is a Member, we might auto-set the parent or hide the field
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('Member');
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
