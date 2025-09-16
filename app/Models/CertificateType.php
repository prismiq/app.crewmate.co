<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificateType extends Model
{
    use HasFactory;

    protected $fillable = ['name','code','validity_months','requires_issue_date','description'];

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}
