<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'item_id', 'quantity', 'loan_date', 'due_date',
        'returned_at', 'purpose', 'status', 'admin_note',
    ];

    public const STATUSES = [
        'pending' => 'Menunggu',
        'approved' => 'Dipinjam',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'returned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isOverdue(): bool
    {
        return $this->status === 'approved' && $this->due_date->isPast() && ! $this->due_date->isToday();
    }
}
