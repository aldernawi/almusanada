<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'company_id',
        'form_limit',
        'submission_limit',
        'upload_limit_mb',
        'can_view_transactions',
        'can_view_all_transactions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    public function canReview(): bool
    {
        return $this->isAdmin() || $this->isReviewer();
    }

    public function canViewAuditing(): bool
    {
        return $this->canReview() || $this->isViewer();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedForms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_reviewer', 'user_id', 'form_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function hasPermission($permission): bool
    {
        $permissionField = 'can_' . $permission;
        return $this->$permissionField ?? false;
    }

    public function canAccess($permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        return $this->hasPermission($permission);
    }
}
