<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'claimed_at',
    ];

    protected $dates = [
        'claimed_at',
    ];

    /**
     * Get the user that owns the voucher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
