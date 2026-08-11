<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('board_id')
                    ->relationship('board', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('stream_id')
                    ->relationship('stream', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->number}")
                    ->label('Semester')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('board.name')
                    ->label('Board / University')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('stream.name')
                    ->label('Stream / Course')
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.number')
                    ->label('Semester')
                    ->formatStateUsing(fn ($state) => $state ? "Sem {$state}" : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('board')
                    ->relationship('board', 'name'),
                Tables\Filters\SelectFilter::make('stream')
                    ->relationship('stream', 'name'),
                Tables\Filters\SelectFilter::make('semester')
                    ->relationship('semester', 'number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Semester {$record->number}"),
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
