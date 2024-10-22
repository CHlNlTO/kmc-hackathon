<?php

namespace App\Filament\Resources\TalentResource\Pages;

use App\Filament\Resources\ResumeResource;
use App\Filament\Resources\TalentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTalent extends CreateRecord
{
    protected static string $resource = ResumeResource::class;

    public static function getSlug(): string
    {
        return 'resume/create';
    }
}
