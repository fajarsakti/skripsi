<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'surveyors_id',
        'surveys_id',
        'pemberi_tugas',
        'industries_id',
        'contract_types_id',
        'lokasi_proyek',
        'assets_id',
        'tanggal_kontrak',
        'selesai_kontrak',
        'status_kontrak',
        'durasi_kontrak',
        'is_available',

    ];

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function surveyors(): BelongsTo
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function contract_types(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function industries(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function assets(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function setStatusKontrakAttribute($value)
    {
        $this->attributes['status_kontrak'] = $value;

        if ($value === 'In Progress') {
            $this->attributes['is_available'] = 1;
        } elseif ($value === 'Selesai' || $value === 'Batal') {
            $this->attributes['is_available'] = 0;
        }
    }
}
