<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use App\Events\SurveyCompleted;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'surveyors_id',
        'pemilik_aset',
        'tanggal_survey',
        'assets_id',
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

    public function assets(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
