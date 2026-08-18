<?php

namespace App\Models;

use Database\Factories\BlockedSenderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedSender extends Model
{
    /** @use HasFactory<BlockedSenderFactory> */
    use HasFactory;

    protected $fillable = [
        'email_address',
        'reason',
    ];
}
