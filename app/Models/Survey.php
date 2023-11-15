<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'surveyors_id',
        'contracts_id',
        'nama_surveyor',
        'pemilik_aset',
        'tanggal_survey',
        'jenis_aset',
        'keterangan_aset',
        'gambar_aset',
        'harga_aset',
    ];

    public function contracts(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function surveyors(): BelongsTo
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function asset(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
