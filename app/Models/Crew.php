<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_code', 'first_name', 'last_name', 'email', 'phone', 'rank',
        'nationality', 'date_of_birth', 'address_line1','address_line2','city','state','postal_code','country',
    ];

    protected $appends = ['full_name'];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function vessels(): BelongsToMany
    {
        return $this->belongsToMany(Vessel::class)
            ->withPivot(['started_at', 'ended_at'])
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
