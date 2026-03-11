<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Song;

class WriterDashboard extends Page
{
    protected string $view = 'filament.pages.writer-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\WriterStats::class,
        ];
    }

    public function getViewData(): array
    {
        return [

            'total_task' => Song::where('writer_id', auth()->id())->count(),

            'assigned' => Song::where('writer_id', auth()->id())
                ->where('status','assigned')
                ->count(),

            'submitted' => Song::where('writer_id', auth()->id())
                ->where('status','submitted')
                ->count(),

            'published' => Song::where('writer_id', auth()->id())
                ->where('status','published')
                ->count(),

        ];
    }
}