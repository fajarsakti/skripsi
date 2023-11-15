<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surveyor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    // public function surveys(): HasMany
    // {
    //     return $this->hasMany(Survey::class);
    // }
}
