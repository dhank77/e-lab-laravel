<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    protected $fillable = ['code', 'name', 'location', 'head', 'description'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
