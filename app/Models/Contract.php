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
        'pemberi_tugas',
        'jenis_industri',
        'tujuan_kontrak',
        'lokasi_proyek',
        'tanggal_survey',
        'selesai_kontrak',
        'status_kontrak',
        'is_available',
        'durasi_kontrak',
    ];

    public function surveys(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function surveyor(): BelongsTo
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function type(): HasMany
    {
        return $this->hasMany(ContractType::class);
    }

    public function industry(): HasMany
    {
        return $this->hasMany(Industry::class);
    }
}
