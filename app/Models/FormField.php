<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormField extends Model
{
    protected $fillable = [
        'form_id',
        'field_type',
        'label',
        'placeholder',
        'help_text',
        'options',
        'default_value',
        'required',
        'order',
        'validation_rules',
        'settings',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'settings' => 'array',
        'required' => 'boolean',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function submissionData(): HasMany
    {
        return $this->hasMany(SubmissionData::class);
    }
}
