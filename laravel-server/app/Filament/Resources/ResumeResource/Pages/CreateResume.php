<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResume extends CreateRecord
{
    protected static string $resource = ResumeResource::class;

    public static function getSlug(): string
    {
        return 'resume/create';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Here you can process the form data before saving
        // For example, you might want to json_encode the skills and education arrays
        $data['skills'] = json_encode($data['skills']);
        $data['skill_details'] = json_encode($data['skill_details']);

        return $data;
    }
}
