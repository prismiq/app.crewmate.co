<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_id','vessel_id','certificate_type_id','reference','issue_date','expiry_date','status','flagged','notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'flagged' => 'bool',
    ];

    public function crew(): BelongsTo
    {
        return $this->belongsTo(Crew::class);
    }

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CertificateType::class, 'certificate_type_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(CertificateFile::class);
    }

    public function computeStatus(?int $expiringDays = 60): string
    {
        if (!$this->expiry_date) {
            return 'valid';
        }
        $today = Carbon::today();
        if ($this->expiry_date->isPast()) return 'expired';
        if ($this->expiry_date->diffInDays($today, false) <= $expiringDays) return 'expiring';
        return 'valid';
    }
}
