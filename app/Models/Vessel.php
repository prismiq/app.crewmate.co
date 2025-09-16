<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vessel extends Model
{
    use HasFactory;

    protected $fillable = ['name','imo_number','flag','owner'];

    public function crews(): BelongsToMany
    {
        return $this->belongsToMany(Crew::class)
            ->withPivot(['started_at','ended_at'])
            ->withTimestamps();
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}
