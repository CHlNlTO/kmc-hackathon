<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateResume extends CreateRecord
{
    protected static string $resource = ResumeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create the talent record
        $talent = static::getModel()::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'job_role_id' => $data['job_role_id'],
        ]);

        // Create talent_skills records
        if (isset($data['skill_details']) && is_array($data['skill_details'])) {
            foreach ($data['skill_details'] as $skillDetail) {
                if (isset($skillDetail['skill']) && isset($skillDetail['years_experience'])) {
                    $talent->skills()->attach($skillDetail['skill'], [
                        'years_experience' => $skillDetail['years_experience'],
                    ]);
                }
            }
        }

        return $talent;
    }
}
