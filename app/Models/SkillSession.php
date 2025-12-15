<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill_id',
        'learner_id',
        'teacher_id',
        'scheduled_at',
        'status'
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
