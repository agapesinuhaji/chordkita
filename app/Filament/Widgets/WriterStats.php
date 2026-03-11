<?php

namespace App\Filament\Widgets;

use App\Models\Song;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WriterStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $writerId = auth()->id();

        return [
            Stat::make(
                'Assigned',
                Song::where('writer_id', $writerId)
                    ->where('status', 'assigned')
                    ->count()
            ),

            Stat::make(
                'Submitted',
                Song::where('writer_id', $writerId)
                    ->where('status', 'submitted')
                    ->count()
            ),

            Stat::make(
                'Published',
                Song::where('writer_id', $writerId)
                    ->where('status', 'published')
                    ->count()
            ),
        ];
    }
}
