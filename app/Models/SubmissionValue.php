<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionValue extends Model
{
    protected $table = 'submission_data';

    protected $fillable = [
        'submission_id',
        'field_id',
        'value',
        'file_data',
    ];

    protected $casts = [
        'file_data' => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
