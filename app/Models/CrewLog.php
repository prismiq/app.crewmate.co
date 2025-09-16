<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrewLog extends Model
{
    use HasFactory;

    protected $fillable = ['crew_id','action','meta'];
    protected $casts = ['meta' => 'array'];

    public function crew(): BelongsTo
    {
        return $this->belongsTo(Crew::class);
    }
}
