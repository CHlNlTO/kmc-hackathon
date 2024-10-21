<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditResume extends EditRecord
{
    protected static string $resource = ResumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Update the talent record
        $record->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'job_role_id' => $data['job_role_id'],
        ]);

        // Update talent_skills records
        if (isset($data['skill_details'])) {
            // Remove existing skills
            $record->skills()->detach();

            // Add new skills
            foreach ($data['skill_details'] as $skillDetail) {
                $record->skills()->attach($skillDetail['skill'], [
                    'years_experience' => $skillDetail['years_experience'],
                ]);
            }
        }

        return $record;
    }
}
