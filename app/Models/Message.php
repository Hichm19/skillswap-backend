<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserMatch;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_match_id',
        'content',
    ];

    /**
     * Le message appartient à un utilisateur (l'auteur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le message appartient à un match/conversation
     */
    public function match()
    {
        return $this->belongsTo(UserMatch::class, 'user_match_id');
    }
}
