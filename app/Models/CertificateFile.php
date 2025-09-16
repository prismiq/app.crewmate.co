<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateFile extends Model
{
    use HasFactory;

    protected $fillable = ['certificate_id','disk','path','original_name','mime_type','size'];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }
}
