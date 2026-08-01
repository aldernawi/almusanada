<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_number',
        'owner_name',
        'details',
        'status',
        'pdf_path',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
