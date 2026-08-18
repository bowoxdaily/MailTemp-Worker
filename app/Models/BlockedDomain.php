<?php

namespace App\Models;

use Database\Factories\BlockedDomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDomain extends Model
{
    /** @use HasFactory<BlockedDomainFactory> */
    use HasFactory;

    protected $fillable = [
        'domain',
        'reason',
    ];
}
