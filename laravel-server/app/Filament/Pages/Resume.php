<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;

class Resume extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.resume';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Create Resume')
                ->url(fn() => ResumeResource::getUrl('create'))
                ->label('Create Resume'),
        ];
    }
}
