<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    public $table = 'talents';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'linkedin_url',
        'job_role_id',
    ];

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'talent_skills');
    }
}
