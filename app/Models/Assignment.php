<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'contracts_id',
        'no_penugasan',
        'surveyors_id',
        'tanggal_penugasan'
    ];

    public function surveyors(): BelongsTo
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function contracts(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
