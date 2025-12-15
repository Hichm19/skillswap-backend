<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkillSession;
use App\Models\User;
use App\Models\Skill;
use Carbon\Carbon;

class SkillSessionSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::take(5)->get();
        $learners = User::skip(5)->take(5)->get();
        $skills = Skill::all();

        foreach (range(1, 10) as $i) {
            SkillSession::create([
                'teacher_id'   => $teachers->random()->id,
                'learner_id'   => $learners->random()->id,
                'skill_id'     => $skills->random()->id,
                'scheduled_at' => Carbon::now()->addDays(rand(1, 10)),  // entre +1 et +10 jours
            ]);
        }
    }
}
