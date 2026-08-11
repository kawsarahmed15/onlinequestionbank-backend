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

                        // Step toggles
                        Forms\Components\Fieldset::make('Required Steps')
                            ->schema([
                                Forms\Components\Toggle::make('onboarding_config.requires_stream')
                                    ->label('Show Stream / Course Step')
                                    ->helperText('Enable to show a "Stream" or "Course" selection step. Use for levels like Class XII (Science/Commerce) or Degree (BA/BSc).')
                                    ->default(false)
                                    ->live(),
                                Forms\Components\Toggle::make('onboarding_config.requires_board')
                                    ->label('Show Board / University Step')
                                    ->helperText('Enable to show a Board or University selection. Almost always required.')
                                    ->default(true)
                                    ->live(),
                                Forms\Components\Toggle::make('onboarding_config.requires_semester')
                                    ->label('Show Semester Step')
                                    ->helperText('Enable for levels where semester-wise papers are organized (e.g. Degree / PG programs).')
                                    ->default(false)
                                    ->live(),
                            ])->columns(3),

                        // Stream/Course labels
                        Forms\Components\Fieldset::make('Stream / Course Step Labels')
                            ->visible(fn (Forms\Get $get) => $get('onboarding_config.requires_stream') === true)
                            ->schema([
                                Forms\Components\TextInput::make('onboarding_config.stream_label')
                                    ->label('Step Label')
                                    ->placeholder('e.g. "Stream", "Course / Degree", "Programme"')
                                    ->helperText('Shown as the section header during onboarding.')
                                    ->default('Stream'),
                                Forms\Components\TextInput::make('onboarding_config.stream_placeholder')
                                    ->label('Dropdown Placeholder')
                                    ->placeholder('e.g. "Select your course (e.g. BA, BSc, MBA)..."')
                                    ->helperText('Hint text shown inside the search box.')
                                    ->default('Select your stream...'),
                                Forms\Components\Textarea::make('onboarding_config.step_descriptions.stream')
                                    ->label('Step Description')
                                    ->placeholder('e.g. "Which post-graduate programme are you enrolled in?"')
                                    ->helperText('Shown below the step header as a subtitle.')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])->columns(2),

                // Board/University labels
                        Forms\Components\Fieldset::make('Board / University Step Labels')
                            ->visible(fn (Forms\Get $get) => $get('onboarding_config.requires_board') === true)
                            ->schema([
                                Forms\Components\Select::make('onboarding_config.board_filter_type')
                                    ->label('Board List Filter')
                                    ->options([
                                        'university' => 'Universities only (for Degree / PG levels)',
                                        'board'      => 'Exam Boards only (for Class X / XII levels)',
                                        ''           => 'Show all (no filter)',
                                    ])
                                    ->default('board')
                                    ->helperText('Controls which entries appear in the board/university picker. "Universities only" shows entries with "University" in their name. "Exam Boards only" hides university entries. Set in admin — no code changes needed.')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('onboarding_config.board_label')
                                    ->label('Step Label')
                                    ->placeholder('e.g. "Exam Board", "University", "Council"')
                                    ->helperText('Shown as the section header (e.g. "Choose your Exam Board" or "Choose your University").')
                                    ->default('Exam Board'),
                                Forms\Components\TextInput::make('onboarding_config.board_placeholder')
                                    ->label('Search Placeholder')
                                    ->placeholder('e.g. "Search your university (e.g. Gauhati University)..."')
                                    ->helperText('Hint text shown in the board/university search box.')
                                    ->default('Search your board...'),
                                Forms\Components\TextInput::make('onboarding_config.board_search_hint')
                                    ->label('Search Hint Text')
                                    ->placeholder('e.g. "Search by state or board name..."')
                                    ->helperText('Secondary hint shown when the board list is empty.')
                                    ->default('Search by name...'),
                                Forms\Components\Textarea::make('onboarding_config.step_descriptions.board')
                                    ->label('Step Description')
                                    ->placeholder('e.g. "Which university are you affiliated with?"')
                                    ->helperText('Shown below the step header as a subtitle.')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // Semester labels
                        Forms\Components\Fieldset::make('Semester Step Labels')
                            ->visible(fn (Forms\Get $get) => $get('onboarding_config.requires_semester') === true)
                            ->schema([
                                Forms\Components\TextInput::make('onboarding_config.total_semesters')
                                    ->label('Total Semesters')
                                    ->numeric()
                                    ->default(6)
                                    ->helperText('Number of semesters to show (e.g. 8 for UG, 4 or 6 for PG). Semester models will be auto-synced upon save.')
                                    ->required(fn (Forms\Get $get) => $get('onboarding_config.requires_semester') === true)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('onboarding_config.semester_label')
                                    ->label('Step Label')
                                    ->placeholder('e.g. "Semester", "Term", "Year"')
                                    ->helperText('Shown as the section header for semester selection.')
                                    ->default('Semester'),
                                Forms\Components\TextInput::make('onboarding_config.semester_placeholder')
                                    ->label('Dropdown Placeholder')
                                    ->placeholder('e.g. "Select your current semester..."')
                                    ->default('Select semester'),
                                Forms\Components\Textarea::make('onboarding_config.step_descriptions.semester')
                                    ->label('Step Description')
                                    ->placeholder('e.g. "Which semester are you currently in?"')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])->columns(2),
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
                Tables\Columns\IconColumn::make('onboarding_config.requires_stream')
                    ->label('Stream Step')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\IconColumn::make('onboarding_config.requires_board')
                    ->label('Board Step')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\IconColumn::make('onboarding_config.requires_semester')
                    ->label('Semester Step')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('onboarding_config.board_label')
                    ->label('Board Label')
                    ->placeholder('—'),
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
