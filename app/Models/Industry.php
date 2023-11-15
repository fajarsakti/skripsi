<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
