<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation Many-to-Many : l'utilisateur possède plusieurs skills
     */
    public function skills()
{
    return $this->belongsToMany(Skill::class, 'user_skills')->withPivot('type');
}

    /**
     * Matches envoyés par cet utilisateur
     * user_id = la personne qui initie ou "est la source"
     */
    public function matches()
    {
        return $this->hasMany(UserMatch::class, 'user_id');
    }

    /**
     * Matches reçus par cet utilisateur
     * matched_user_id = la personne "cible"
     */
    public function matchedBy()
    {
        return $this->hasMany(UserMatch::class, 'matched_user_id');
    }

    /**
     * Tous les matches combinés (envoyés + reçus)
     * Permet de récupérer toutes les connexions de l'utilisateur
     */
    public function allMatches()
    {
        return $this->matches->merge($this->matchedBy);
    }

    /**
     * Un utilisateur peut avoir plusieurs sessions de skills
     */
    public function skillSessions()
    {
        return $this->hasMany(SkillSession::class);
    }
}
