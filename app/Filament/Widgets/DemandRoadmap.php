<?php

namespace App\Filament\Widgets;

use App\Models\Request;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class DemandRoadmap extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Request::query()
                    ->select('subject_id', 'year', 'paper_set', DB::raw('count(*) as demand_count'))
                    ->where('status', 'pending')
                    ->groupBy('subject_id', 'year', 'paper_set')
                    ->orderBy('demand_count', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.board.name')
                    ->label('Board')
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Requested Year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper_set')
                    ->label('Paper Set')
                    ->sortable(),
                Tables\Columns\TextColumn::make('demand_count')
                    ->label('Total User Requests')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
            ]);
    }
}
