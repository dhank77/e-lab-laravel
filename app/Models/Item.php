<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['lab_id', 'code', 'name', 'category', 'stock', 'condition', 'description'];

    public const CONDITIONS = [
        'baik' => 'Baik',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat' => 'Rusak Berat',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /** Jumlah barang yang sedang dipinjam (status disetujui). */
    public function borrowedCount(): int
    {
        return (int) $this->loans()->where('status', 'approved')->sum('quantity');
    }

    /** Jumlah barang yang masih tersedia untuk dipinjam. */
    public function availableStock(): int
    {
        if ($this->condition === 'rusak_berat') {
            return 0;
        }

        return max(0, $this->stock - $this->borrowedCount());
    }

    public function conditionLabel(): string
    {
        return self::CONDITIONS[$this->condition] ?? $this->condition;
    }
}
