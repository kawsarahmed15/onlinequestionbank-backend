<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaperResource\Pages;
use App\Models\Paper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaperResource extends Resource
{
    protected static ?string $model = Paper::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level_id')
                    ->label('Class / Program')
                    ->options(\App\Models\Level::all()->pluck('name', 'id'))
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Set $set, $state, $record) {
                        if ($record) {
                            if ($record->stream_id && $record->stream) {
                                $set('level_id', $record->stream->level_id);
                            } elseif ($record->semester_id && $record->semester) {
                                $set('level_id', $record->semester->level_id);
                            } else {
                                $classX = \App\Models\Level::where('name', 'Class X')->first();
                                if ($classX) {
                                    $set('level_id', $classX->id);
                                }
                            }
                        }
                    })
                    ->afterStateUpdated(fn (Set $set) => $set('subject_id', null)),

                Forms\Components\Select::make('stream_id')
                    ->label('Stream / Course')
                    ->options(fn (Get $get) => \App\Models\Stream::where('level_id', $get('level_id'))->pluck('name', 'id'))
                    ->live()
                    ->visible(fn (Get $get) => !empty($get('level_id')) && \App\Models\Level::find($get('level_id'))?->name !== 'Class X')
                    ->afterStateUpdated(fn (Set $set) => $set('subject_id', null)),

                Forms\Components\Select::make('semester_id')
                    ->label('Semester')
                    ->options(fn (Get $get) => \App\Models\Semester::where('level_id', $get('level_id'))->pluck('number', 'id')->mapWithKeys(fn ($num, $id) => [$id => "Semester $num"]))
                    ->live()
                    ->visible(fn (Get $get) => !empty($get('level_id')) && \App\Models\Level::find($get('level_id'))?->name === 'Degree')
                    ->afterStateUpdated(fn (Set $set) => $set('subject_id', null)),

                Forms\Components\Select::make('board_id')
                    ->label('Board / University')
                    ->options(\App\Models\Board::all()->pluck('name', 'id'))
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn (Set $set) => $set('subject_id', null)),

                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function (Get $get) {
                        $boardId = $get('board_id');
                        $streamId = $get('stream_id');
                        $semesterId = $get('semester_id');
                        $levelId = $get('level_id');

                        if (!$boardId) return [];

                        $query = \App\Models\Subject::where('board_id', $boardId);

                        if ($levelId && \App\Models\Level::find($levelId)?->name === 'Class X') {
                            $query->whereNull('stream_id')->whereNull('semester_id');
                        } else {
                            if ($streamId) {
                                $query->where('stream_id', $streamId);
                            }
                            if ($semesterId) {
                                $query->where('semester_id', $semesterId);
                            }
                        }

                        return $query->pluck('name', 'id');
                    }),

                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->minValue(2000),
                Forms\Components\Select::make('paper_set')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                    ]),
                Forms\Components\Select::make('exam_type')
                    ->options([
                        'annual' => 'Annual',
                        'supplementary' => 'Supplementary',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('file_path')
                    ->disk('private')
                    ->directory('papers')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper_set')
                    ->sortable(),
                Tables\Columns\TextColumn::make('exam_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('download_count')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject')
                    ->relationship('subject', 'name'),
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
            'index' => Pages\ListPapers::route('/'),
            'create' => Pages\CreatePaper::route('/create'),
            'edit' => Pages\EditPaper::route('/{record}/edit'),
        ];
    }
}
