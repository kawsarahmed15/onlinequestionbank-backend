<?php

namespace App\Filament\Widgets;

use App\Models\Paper;
use App\Models\Submission;
use App\Models\Subscription;
use App\Models\Request as PaperRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $pendingSubmissions = Submission::where('status', 'pending')->count();
        $activeSubs = Subscription::where('status', 'active')->where('ends_at', '>', now())->count();
        
        $topRequest = PaperRequest::groupBy('subject_id')
            ->select('subject_id', DB::raw('count(*) as req_count'))
            ->orderBy('req_count', 'desc')
            ->first();
            
        $topRequestedSubject = $topRequest && $topRequest->subject 
            ? $topRequest->subject->name . " ({$topRequest->req_count} requests)"
            : 'None';

        return [
            Stat::make('Total Exam Papers', Paper::count())
                ->description('Active, verified previous year papers')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
                
            Stat::make('Pending Moderations', $pendingSubmissions)
                ->description('Student paper uploads waiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingSubmissions > 0 ? 'warning' : 'gray'),
                
            Stat::make('Active Premium Users', $activeSubs)
                ->description('Subscribed premium students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            Stat::make('Top Requested Subject', $topRequestedSubject)
                ->description('Highest student content demand')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
        ];
    }
}
