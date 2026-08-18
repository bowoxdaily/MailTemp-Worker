<?php

namespace App\Models;

use Database\Factories\EmailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Email extends Model
{
    /** @use HasFactory<EmailFactory> */
    use HasFactory;

    protected $fillable = [
        'temporary_email_id',
        'from_address',
        'from_name',
        'subject',
        'body_html',
        'body_text',
        'size_bytes',
        'is_read',
        'received_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'received_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<TemporaryEmail, $this>
     */
    public function temporaryEmail(): BelongsTo
    {
        return $this->belongsTo(TemporaryEmail::class);
    }

    /**
     * @return HasMany<Attachment, $this>
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}
