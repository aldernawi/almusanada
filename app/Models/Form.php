<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Form extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'description',
        'status',
        'settings',
        'slug',
        'thank_you_message',
        'require_login',
        'enable_captcha',
        'webhook_url',
        'published_at',
        'is_favorite',
        'archived_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
        'require_login' => 'boolean',
        'enable_captcha' => 'boolean',
        'is_favorite' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'form_folder');
    }

    public function reviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'form_reviewer', 'form_id', 'user_id');
    }

    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'active')->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeFavorite($query)
    {
        return $query->where('is_favorite', true);
    }
}
