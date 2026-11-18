<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRequest extends Model
{
    public const STATUSES = [
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'dipinjam' => 'Sedang Dipinjam',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
        'dibatalkan' => 'Dibatalkan',
    ];

    protected $fillable = ['user_id', 'equipment_id', 'equipment_name', 'quantity', 'borrow_date', 'return_date', 'purpose'];

    protected function casts(): array
    {
        return ['handed_over_at' => 'datetime', 'returned_at' => 'datetime'];
    }

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}