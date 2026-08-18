<?php

namespace App\Models;

use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    /** @use HasFactory<DomainFactory> */
    use HasFactory;

    protected $fillable = [
        'domain',
        'is_active',
        'cloudflare_zone_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<TemporaryEmail, $this>
     */
    public function temporaryEmails(): HasMany
    {
        return $this->hasMany(TemporaryEmail::class);
    }
}
