<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * User Roles
     */
    const ROLE_CHAIRMAN = 'chairman';
    const ROLE_DIRECTOR = 'director';
    const ROLE_PRINCIPAL = 'principal';
    const ROLE_ICT = 'ict-team';
    const ROLE_SCHOOL_STAFF = 'school-staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'school_id',
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function isPrincipal()
    {
        return $this->hasRole(self::ROLE_PRINCIPAL);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    /**
     * Check if the user is an admin (Chairman or Director).
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_CHAIRMAN, self::ROLE_DIRECTOR]);
    }

    /**
     * Get the human-readable label for the user's role.
     *
     * @return string
     */
    public function roleLabel()
    {
        return match($this->role) {
            self::ROLE_CHAIRMAN => 'Chairman',
            self::ROLE_DIRECTOR => 'Director',
            self::ROLE_PRINCIPAL => 'Principal',
            self::ROLE_ICT => 'ICT Team',
            self::ROLE_SCHOOL_STAFF => 'School Staff',
            default => 'Building Admin',
        };
    }

    public function isSchoolStaff()
    {
        return $this->hasRole(self::ROLE_SCHOOL_STAFF);
    }
}
