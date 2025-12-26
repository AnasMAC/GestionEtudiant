<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', // Critical: Links this profile to a specific login account
        'cne',
        'filiere',
    ];

    /**
     * Relationship: A Student profile belongs to a User.
     * We use this to get the name and email: $student->user->name
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
