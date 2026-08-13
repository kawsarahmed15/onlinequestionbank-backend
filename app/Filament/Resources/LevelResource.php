<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Academic Taxonomy';

    protected static ?string $navigationLabel = 'Academic Levels';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ─────────────────────────────────────────
                // Basic Info
                // ─────────────────────────────────────────
                Forms\Components\Section::make('Basic Information')
                    ->description('Define the academic level name and display order.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Class X, Class XII, Degree, Post Graduation')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->placeholder('Brief description shown to students during onboarding...')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower number = shown first in the list.'),
                    ]),

                // ─────────────────────────────────────────
                // Onboarding Flow Configuration
                // ─────────────────────────────────────────
                Forms\Components\Section::make('Onboarding Flow Configuration')
                    ->description('Control exactly which steps appear in the student onboarding flow for this academic level. This makes the flow dynamic — no code changes needed.')
                    ->schema([
                        Forms\Components\Repeater::make('onboarding_config.steps')
                            ->label('Onboarding Steps')
                            ->helperText('Define the steps in order. Drag to reorder. Each level can have different steps in any order.')
                            ->reorderable()
                            ->addActionLabel('+ Add Step')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Step Type')
                                    ->options([
                                        'stream'   => 'Stream / Course selection',
                                        'board'    => 'Board / University selection',
                                        'semester' => 'Semester / Term selection',
                                    ])
                                    ->required()
                                    ->live(),
                                
                                Forms\Components\TextInput::make('label')
                                    ->label('Step Label')
                                    ->placeholder('e.g. "Stream", "University", "Semester"')
                                    ->helperText('Section header shown to student during onboarding.')
                                    ->required(),
                                
                                Forms\Components\Textarea::make('description')
                                    ->label('Step Description')
                                    ->placeholder('e.g. "Which programme are you enrolled in?"')
                                    ->helperText('Subtitle shown below the header.')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                
                                Forms\Components\TextInput::make('placeholder')
                                    ->label('Placeholder / Hint')
                                    ->placeholder('e.g. "Select your course..."')
                                    ->helperText('Text shown inside the search/dropdown field.')
                                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['stream', 'board'])),
                                
                                Forms\Components\TextInput::make('search_hint')
                                    ->label('Empty State Hint')
                                    ->placeholder('e.g. "Search by university name..."')
                                    ->helperText('Shown when no results yet.')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'board'),
                                
                                Forms\Components\Select::make('filter_type')
                                    ->label('Board List Filter')
                                    ->options([
                                        'university' => 'Universities only',
                                        'board'      => 'Exam Boards only',
                                        ''           => 'Show all',
                                    ])
                                    ->default('board')
                                    ->helperText('Controls which boards appear in this step.')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'board'),
                                
                                Forms\Components\TextInput::make('total')
                                    ->label('Total Count')
                                    ->numeric()
                                    ->default(8)
                                    ->helperText('Number of semesters/terms to show (e.g. 8 for UG, 4 for PG).')
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'semester'),
                                
                                Forms\Components\TextInput::make('icon')
                                    ->label('Icon Name')
                                    ->placeholder('e.g. menu_book, account_balance, calendar_month')
                                    ->helperText('Material icon name shown in the Flutter app.')
                                    ->default('school'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['type']) ? ucfirst($state['type']) . ': ' . ($state['label'] ?? '') : null
                            )
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),
                Tables\Columns\TextColumn::make('onboarding_config.steps')
                    ->label('Steps Configured')
                    ->formatStateUsing(function ($state) {
                        if (!is_array($state)) return 'None';
                        $types = collect($state)->pluck('type')->map(fn($t) => ucfirst($t))->implode(', ');
                        return count($state) . ' steps (' . $types . ')';
                    })
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([])
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
            'index'  => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit'   => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
