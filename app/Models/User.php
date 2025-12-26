<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added 'role' so we can mass-assign it (admin/student)
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship: A User can have one Student profile.
     * (Returns null if the user is an Admin).
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Helper to check if the user is an Admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
