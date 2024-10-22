<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Filament\Resources\ResumeResource;
use Filament\Actions\Action;

class ResumeWidget extends Widget
{
    protected static string $view = 'filament.widgets.create-resume';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Create Resume')
                ->url(fn() => ResumeResource::getUrl('create'))
                ->label('Create Resume'),
        ];
    }
}
