<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // Si tu veux éviter les doublons lors de ré-exécution, tu peux vider la table ici :
        // Skill::truncate();

        Skill::insert([
            ['name' => 'Laravel',         'categorie' => 'Backend'],
            ['name' => 'React.js',        'categorie' => 'Frontend'],
            ['name' => 'Node.js',         'categorie' => 'Backend'],
            ['name' => 'Flutter',         'categorie' => 'Mobile'],
            ['name' => 'Python',          'categorie' => 'Backend'],
            ['name' => 'Django',          'categorie' => 'Backend'],
            ['name' => 'JavaScript',      'categorie' => 'Frontend'],
            ['name' => 'TypeScript',      'categorie' => 'Frontend'],
            ['name' => 'DevOps',          'categorie' => 'DevOps'],
            ['name' => 'Docker',          'categorie' => 'DevOps'],
            ['name' => 'Kubernetes',      'categorie' => 'DevOps'],
            ['name' => 'SQL',             'categorie' => 'Base de données'],
            ['name' => 'MongoDB',         'categorie' => 'Base de données'],
            ['name' => 'Next.js',         'categorie' => 'Frontend'],
            ['name' => 'Vue.js',          'categorie' => 'Frontend'],
            ['name' => 'Java',            'categorie' => 'Backend'],
            ['name' => 'Spring Boot',     'categorie' => 'Backend'],
            ['name' => 'Machine Learning','categorie' => 'Data/IA'],
            ['name' => 'Cybersecurity',   'categorie' => 'Sécurité'],
            ['name' => 'Cloud Computing', 'categorie' => 'Cloud'],
        ]);
    }
}
