<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile_number')
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('school_college_name')
                    ->maxLength(255),
                Forms\Components\Select::make('onboarded_level_id')
                    ->relationship('level', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('onboarded_stream_id')
                    ->relationship('stream', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('onboarded_board_id')
                    ->relationship('board', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('onboarded_semester_id')
                    ->relationship('semester', 'number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->number}")
                    ->label('Onboarded Semester')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('role')
                    ->options([
                        'student' => 'Student',
                        'admin' => 'Admin',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile_number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('school_college_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'student' => 'Student',
                        'admin' => 'Admin',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resetDevices')
                    ->label('Reset Devices')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (\App\Models\User $record) => $record->devices()->delete()),
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
