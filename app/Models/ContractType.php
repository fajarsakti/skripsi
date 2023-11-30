<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
