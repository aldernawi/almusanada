<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionData extends Model
{
    protected $fillable = [
        'submission_id',
        'field_id',
        'value',
        'file_data',
    ];

    protected $casts = [
        'file_data' => 'array',
        'value' => 'encrypted',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class, 'submission_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }
}
