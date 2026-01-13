<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class UserMatch extends Model
{
    protected $table = 'user_matches';

    protected $fillable = [
        'user_id',
        'matched_user_id',
        'score',
    ];

    // Relation vers l'utilisateur principal
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation vers l'utilisateur matched
    public function matchedUser()
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }
}
